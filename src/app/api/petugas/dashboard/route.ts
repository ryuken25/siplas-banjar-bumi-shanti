import { NextResponse } from 'next/server';
import { getSession } from '@/lib/auth';
import { sql } from '@/lib/db';

export async function GET() {
  try {
    const session = await getSession();
    if (!session || session.role !== 'petugas') {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const today = new Date().toISOString().split('T')[0];

    const [baruResult, diprosesResult, selesaiHariIniResult, menungguVerifikasiResult, laporanTerbaru, chartData] = await Promise.all([
      sql`SELECT COUNT(*)::int as count FROM laporan_sampah WHERE status = 'dikirim'`,
      sql`SELECT COUNT(*)::int as count FROM laporan_sampah WHERE status IN ('diterima', 'diproses')`,
      sql`SELECT COUNT(*)::int as count FROM laporan_sampah WHERE status = 'selesai' AND DATE(tanggal_selesai) = ${today}`,
      sql`SELECT COUNT(*)::int as count FROM iuran_bulanan WHERE status = 'menunggu_verifikasi'`,
      sql`SELECT ls.*, u.name as pelapor_name FROM laporan_sampah ls JOIN users u ON ls.user_id = u.id ORDER BY ls.created_at DESC LIMIT 5`,
      sql`SELECT DATE(created_at)::text as date, COUNT(*)::int as count FROM laporan_sampah WHERE created_at >= NOW() - INTERVAL '7 days' GROUP BY DATE(created_at) ORDER BY date`,
    ]);

    // Fill in missing days for chart
    const chartDays: { date: string; count: number }[] = [];
    for (let i = 6; i >= 0; i--) {
      const d = new Date();
      d.setDate(d.getDate() - i);
      const dateStr = d.toISOString().split('T')[0];
      const found = (chartData as any[]).find((r: any) => r.date === dateStr);
      chartDays.push({ date: dateStr, count: found ? found.count : 0 });
    }

    return NextResponse.json({
      stat: {
        baru: baruResult[0]?.count || 0,
        diproses: diprosesResult[0]?.count || 0,
        selesai_hari_ini: selesaiHariIniResult[0]?.count || 0,
        menunggu_verifikasi: menungguVerifikasiResult[0]?.count || 0,
      },
      laporanTerbaru: laporanTerbaru,
      chart: chartDays,
    });
  } catch (error) {
    console.error('Petugas dashboard error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
