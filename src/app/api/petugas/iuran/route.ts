import { NextResponse } from 'next/server';
import { getSession } from '@/lib/auth';
import { sql } from '@/lib/db';

export async function GET(request: Request) {
  try {
    const session = await getSession();
    if (!session || session.role !== 'petugas') {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const { searchParams } = new URL(request.url);
    const page = parseInt(searchParams.get('page') || '1');
    const limit = parseInt(searchParams.get('limit') || '12');
    const offset = (page - 1) * limit;

    const countResult = await sql`SELECT COUNT(*)::int as total FROM iuran_bulanan WHERE status = 'menunggu_verifikasi'`;
    const total = countResult[0]?.total || 0;

    const data = await sql`
      SELECT ib.*, u.name as warga_name, u.no_telp as warga_telp
      FROM iuran_bulanan ib
      JOIN users u ON ib.user_id = u.id
      WHERE ib.status = 'menunggu_verifikasi'
      ORDER BY ib.created_at DESC
      LIMIT ${limit} OFFSET ${offset}
    `;

    return NextResponse.json({
      data,
      pagination: { page, limit, total, totalPages: Math.ceil(total / limit) },
    });
  } catch (error) {
    console.error('Petugas iuran list error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
