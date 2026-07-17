import { NextResponse } from 'next/server';
import { getSession } from '@/lib/auth';
import { sql } from '@/lib/db';

export async function GET() {
  try {
    const session = await getSession();
    if (!session || session.role !== 'admin') {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const now = new Date();
    const currentMonth = now.getMonth() + 1;
    const currentYear = now.getFullYear();

    const [
      wargaAktifResult,
      laporanBulanIniResult,
      iuranTerkumpulResult,
      totalLaporanResult,
      selesaiLaporanResult,
      pendingApprovalResult,
      iuranLabels,
      statusDist,
      aktivitasTerbaru,
    ] = await Promise.all([
      sql`SELECT COUNT(*)::int as count FROM users WHERE role = 'warga' AND status_akun = 'aktif'`,
      sql`SELECT COUNT(*)::int as count FROM laporan_sampah WHERE EXTRACT(MONTH FROM created_at) = ${currentMonth} AND EXTRACT(YEAR FROM created_at) = ${currentYear}`,
      sql`SELECT COALESCE(SUM(nominal), 0)::int as total FROM iuran_bulanan WHERE status = 'lunas' AND EXTRACT(MONTH FROM tanggal_verifikasi) = ${currentMonth} AND EXTRACT(YEAR FROM tanggal_verifikasi) = ${currentYear}`,
      sql`SELECT COUNT(*)::int as count FROM laporan_sampah WHERE EXTRACT(MONTH FROM created_at) = ${currentMonth} AND EXTRACT(YEAR FROM created_at) = ${currentYear}`,
      sql`SELECT COUNT(*)::int as count FROM laporan_sampah WHERE status = 'selesai' AND EXTRACT(MONTH FROM created_at) = ${currentMonth} AND EXTRACT(YEAR FROM created_at) = ${currentYear}`,
      sql`SELECT COUNT(*)::int as count FROM users WHERE role = 'warga' AND status_akun = 'pending'`,
      // Iuran labels - last 6 months
      sql`SELECT bulan, tahun, COALESCE(SUM(nominal), 0)::int as total FROM iuran_bulanan WHERE status = 'lunas' AND (tahun > ${currentYear} - 1 OR (tahun = ${currentYear} AND bulan >= ${currentMonth} - 5)) GROUP BY tahun, bulan ORDER BY tahun, bulan`,
      // Status distribution
      sql`SELECT status, COUNT(*)::int as count FROM laporan_sampah WHERE EXTRACT(MONTH FROM created_at) = ${currentMonth} AND EXTRACT(YEAR FROM created_at) = ${currentYear} GROUP BY status`,
      // Recent activity
      sql`SELECT ls.id, ls.kode_laporan, ls.status, ls.jenis_sampah, ls.created_at, u.name as pelapor_name FROM laporan_sampah ls JOIN users u ON ls.user_id = u.id ORDER BY ls.created_at DESC LIMIT 8`,
    ]);

    const totalLaporan = totalLaporanResult[0]?.count || 0;
    const selesaiLaporan = selesaiLaporanResult[0]?.count || 0;
    const tingkatPenyelesaian = totalLaporan > 0 ? Math.round((selesaiLaporan / totalLaporan) * 100) : 0;

    // Build iuran chart data for last 6 months
    const iuranChart: { label: string; total: number }[] = [];
    const BULAN_LABEL: Record<number, string> = {
      1: 'Jan', 2: 'Feb', 3: 'Mar', 4: 'Apr', 5: 'Mei', 6: 'Jun',
      7: 'Jul', 8: 'Agu', 9: 'Sep', 10: 'Okt', 11: 'Nov', 12: 'Des',
    };
    for (let i = 5; i >= 0; i--) {
      let m = currentMonth - i;
      let y = currentYear;
      if (m <= 0) { m += 12; y--; }
      const found = (iuranLabels as any[]).find((r: any) => r.bulan === m && r.tahun === y);
      iuranChart.push({ label: `${BULAN_LABEL[m]} ${y}`, total: found ? found.total : 0 });
    }

    return NextResponse.json({
      totalWargaAktif: wargaAktifResult[0]?.count || 0,
      laporanBulanIni: laporanBulanIniResult[0]?.count || 0,
      iuranTerkumpul: iuranTerkumpulResult[0]?.total || 0,
      tingkatPenyelesaian,
      iuranChart,
      statusDist: statusDist,
      aktivitasTerbaru: aktivitasTerbaru,
      pendingApproval: pendingApprovalResult[0]?.count || 0,
    });
  } catch (error) {
    console.error('Admin dashboard error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
