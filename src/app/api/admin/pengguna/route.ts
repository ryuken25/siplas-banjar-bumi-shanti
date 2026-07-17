import { NextResponse } from 'next/server';
import { getSession } from '@/lib/auth';
import { sql } from '@/lib/db';

export async function GET(request: Request) {
  try {
    const session = await getSession();
    if (!session || session.role !== 'admin') {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const { searchParams } = new URL(request.url);
    const tab = searchParams.get('tab') || 'pending';
    const search = searchParams.get('search');
    const page = parseInt(searchParams.get('page') || '1');
    const limit = parseInt(searchParams.get('limit') || '12');
    const offset = (page - 1) * limit;

    let conditions: string[] = ["role = 'warga'"];
    let params: any[] = [];
    let paramIdx = 1;

    if (tab && tab !== 'all') {
      conditions.push(`status_akun = $${paramIdx++}`);
      params.push(tab);
    }
    if (search) {
      conditions.push(`(name ILIKE $${paramIdx} OR email ILIKE $${paramIdx} OR nik ILIKE $${paramIdx})`);
      params.push(`%${search}%`);
      paramIdx++;
    }

    const whereClause = conditions.length > 0 ? `WHERE ${conditions.join(' AND ')}` : '';

    const countQuery = `SELECT COUNT(*)::int as total FROM users ${whereClause}`;
    const dataQuery = `
      SELECT id, name, email, nik, no_kk, no_telp, alamat, foto_profil, role, status_akun, alasan_tolak_akun, created_at, updated_at
      FROM users ${whereClause}
      ORDER BY created_at DESC
      LIMIT $${paramIdx++} OFFSET $${paramIdx++}
    `;
    params.push(limit, offset);

    const countsQuery = sql`SELECT status_akun, COUNT(*)::int as count FROM users WHERE role = 'warga' GROUP BY status_akun`;
    const [countResult, data, counts] = await Promise.all([
      sql(countQuery, params.slice(0, paramIdx - 3)),
      sql(dataQuery, params),
      countsQuery,
    ]);

    const countsMap: Record<string, number> = { pending: 0, aktif: 0, nonaktif: 0 };
    for (const row of counts as any[]) {
      countsMap[row.status_akun] = row.count;
    }

    return NextResponse.json({
      data,
      counts: countsMap,
      pagination: {
        page,
        limit,
        total: countResult[0]?.total || 0,
        totalPages: Math.ceil((countResult[0]?.total || 0) / limit),
      },
    });
  } catch (error) {
    console.error('Admin pengguna list error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
