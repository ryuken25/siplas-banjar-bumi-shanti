import { NextResponse } from 'next/server';
import { getSession } from '@/lib/auth';
import { sql } from '@/lib/db';

export async function GET() {
  const session = await getSession();
  if (!session || session.role !== 'warga') {
    return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
  }

  const userId = session.userId;

  const [laporanTerbaru, tagihanBelum, statAktif, statSelesai, statTagihanBelum, statTagihanLunas] = await Promise.all([
    sql`SELECT * FROM laporan_sampah WHERE user_id = ${userId} ORDER BY created_at DESC LIMIT 3`,
    sql`SELECT * FROM iuran_bulanan WHERE user_id = ${userId} AND status IN ('belum_bayar', 'ditolak') ORDER BY tahun DESC, bulan DESC LIMIT 3`,
    sql`SELECT COUNT(*)::int as count FROM laporan_sampah WHERE user_id = ${userId} AND status IN ('dikirim', 'diterima', 'diproses')`,
    sql`SELECT COUNT(*)::int as count FROM laporan_sampah WHERE user_id = ${userId} AND status = 'selesai'`,
    sql`SELECT COUNT(*)::int as count FROM iuran_bulanan WHERE user_id = ${userId} AND status IN ('belum_bayar', 'ditolak')`,
    sql`SELECT COUNT(*)::int as count FROM iuran_bulanan WHERE user_id = ${userId} AND status = 'lunas'`,
  ]);

  return NextResponse.json({
    laporanTerbaru,
    tagihanBelum,
    stat: {
      laporan_aktif: statAktif[0]?.count ?? 0,
      laporan_selesai: statSelesai[0]?.count ?? 0,
      tagihan_belum: statTagihanBelum[0]?.count ?? 0,
      tagihan_lunas: statTagihanLunas[0]?.count ?? 0,
    },
  });
}
