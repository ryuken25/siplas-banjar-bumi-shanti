import { NextResponse } from 'next/server';
import { getSession } from '@/lib/auth';
import { sql } from '@/lib/db';

export async function GET(request: Request, { params }: { params: { id: string } }) {
  try {
    const session = await getSession();
    if (!session || session.role !== 'admin') {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const { id } = params;
    const rows = await sql`
      SELECT ls.*, u.name as pelapor_name, u.email as pelapor_email, u.no_telp as pelapor_telp, u.alamat as pelapor_alamat,
             p.name as petugas_name
      FROM laporan_sampah ls
      JOIN users u ON ls.user_id = u.id
      LEFT JOIN users p ON ls.petugas_id = p.id
      WHERE ls.id = ${id}
    `;

    if (rows.length === 0) {
      return NextResponse.json({ error: 'Laporan tidak ditemukan' }, { status: 404 });
    }

    return NextResponse.json(rows[0]);
  } catch (error) {
    console.error('Admin laporan detail error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
