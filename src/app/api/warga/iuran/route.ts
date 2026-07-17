import { NextResponse } from 'next/server';
import { getSession } from '@/lib/auth';
import { sql } from '@/lib/db';

export async function GET() {
  const session = await getSession();
  if (!session || session.role !== 'warga') {
    return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
  }

  const [aktif, riwayat] = await Promise.all([
    sql`
      SELECT * FROM iuran_bulanan
      WHERE user_id = ${session.userId} AND status IN ('belum_bayar', 'menunggu_verifikasi', 'ditolak')
      ORDER BY tahun DESC, bulan DESC
    `,
    sql`
      SELECT * FROM iuran_bulanan
      WHERE user_id = ${session.userId} AND status = 'lunas'
      ORDER BY tahun DESC, bulan DESC
    `,
  ]);

  return NextResponse.json({ aktif, riwayat });
}
