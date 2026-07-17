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
    const status = searchParams.get('status');
    const jenis = searchParams.get('jenis');
    const search = searchParams.get('search');
    const from = searchParams.get('from');
    const to = searchParams.get('to');
    const page = parseInt(searchParams.get('page') || '1');
    const limit = parseInt(searchParams.get('limit') || '12');
    const offset = (page - 1) * limit;

    let conditions: string[] = [];
    let params: any[] = [];
    let paramIdx = 1;

    if (status) { conditions.push(`ls.status = $${paramIdx++}`); params.push(status); }
    if (jenis) { conditions.push(`ls.jenis_sampah = $${paramIdx++}`); params.push(jenis); }
    if (search) {
      conditions.push(`(ls.kode_laporan ILIKE $${paramIdx} OR ls.lokasi_text ILIKE $${paramIdx} OR u.name ILIKE $${paramIdx})`);
      params.push(`%${search}%`); paramIdx++;
    }
    if (from) { conditions.push(`ls.created_at >= $${paramIdx++}`); params.push(from); }
    if (to) { conditions.push(`ls.created_at <= $${paramIdx++}`); params.push(to + 'T23:59:59'); }

    const whereClause = conditions.length > 0 ? `WHERE ${conditions.join(' AND ')}` : '';

    const countQuery = `SELECT COUNT(*)::int as total FROM laporan_sampah ls JOIN users u ON ls.user_id = u.id ${whereClause}`;
    const dataQuery = `
      SELECT ls.*, u.name as pelapor_name, p.name as petugas_name
      FROM laporan_sampah ls
      JOIN users u ON ls.user_id = u.id
      LEFT JOIN users p ON ls.petugas_id = p.id
      ${whereClause}
      ORDER BY ls.created_at DESC
      LIMIT $${paramIdx++} OFFSET $${paramIdx++}
    `;
    params.push(limit, offset);

    const [countResult, data] = await Promise.all([
      sql(countQuery, params.slice(0, paramIdx - 3)),
      sql(dataQuery, params),
    ]);

    return NextResponse.json({
      data,
      pagination: {
        page, limit,
        total: countResult[0]?.total || 0,
        totalPages: Math.ceil((countResult[0]?.total || 0) / limit),
      },
    });
  } catch (error) {
    console.error('Admin laporan list error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
