import { NextResponse } from 'next/server';
import { getSession } from '@/lib/auth';
import { sql } from '@/lib/db';

export async function GET(
  _request: Request,
  { params }: { params: { id: string } }
) {
  const session = await getSession();
  if (!session || session.role !== 'warga') {
    return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
  }

  const laporan = await sql`
    SELECT ls.*, u.name as petugas_name
    FROM laporan_sampah ls
    LEFT JOIN users u ON ls.petugas_id = u.id
    WHERE ls.id = ${params.id} AND ls.user_id = ${session.userId}
    LIMIT 1
  `;

  if (!laporan[0]) {
    return NextResponse.json({ error: 'Laporan tidak ditemukan' }, { status: 404 });
  }

  return NextResponse.json(laporan[0]);
}
