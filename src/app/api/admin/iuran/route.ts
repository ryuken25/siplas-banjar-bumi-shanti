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
    const year = searchParams.get('year') || String(new Date().getFullYear());
    const month = searchParams.get('month');
    const status = searchParams.get('status');

    // Build base conditions
    let yearInt = parseInt(year);
    
    // Data query
    const data = month && status
      ? await sql`SELECT ib.*, u.name as warga_name, v.name as verifikator_name FROM iuran_bulanan ib JOIN users u ON ib.user_id = u.id LEFT JOIN users v ON ib.verifikator_id = v.id WHERE ib.tahun = ${yearInt} AND ib.bulan = ${parseInt(month)} AND ib.status = ${status} ORDER BY ib.tahun DESC, ib.bulan DESC, ib.created_at DESC LIMIT 100`
      : month
      ? await sql`SELECT ib.*, u.name as warga_name, v.name as verifikator_name FROM iuran_bulanan ib JOIN users u ON ib.user_id = u.id LEFT JOIN users v ON ib.verifikator_id = v.id WHERE ib.tahun = ${yearInt} AND ib.bulan = ${parseInt(month)} ORDER BY ib.tahun DESC, ib.bulan DESC, ib.created_at DESC LIMIT 100`
      : status
      ? await sql`SELECT ib.*, u.name as warga_name, v.name as verifikator_name FROM iuran_bulanan ib JOIN users u ON ib.user_id = u.id LEFT JOIN users v ON ib.verifikator_id = v.id WHERE ib.tahun = ${yearInt} AND ib.status = ${status} ORDER BY ib.tahun DESC, ib.bulan DESC, ib.created_at DESC LIMIT 100`
      : await sql`SELECT ib.*, u.name as warga_name, v.name as verifikator_name FROM iuran_bulanan ib JOIN users u ON ib.user_id = u.id LEFT JOIN users v ON ib.verifikator_id = v.id WHERE ib.tahun = ${yearInt} ORDER BY ib.tahun DESC, ib.bulan DESC, ib.created_at DESC LIMIT 100`;

    // Summary
    const summaryRows = month
      ? await sql`SELECT COUNT(*) FILTER (WHERE status = 'lunas')::int as lunas, COUNT(*) FILTER (WHERE status = 'menunggu_verifikasi')::int as menunggu, COUNT(*) FILTER (WHERE status = 'belum_bayar')::int as belum, COALESCE(SUM(nominal) FILTER (WHERE status = 'lunas'), 0)::int as total_nominal FROM iuran_bulanan WHERE tahun = ${yearInt} AND bulan = ${parseInt(month)}`
      : await sql`SELECT COUNT(*) FILTER (WHERE status = 'lunas')::int as lunas, COUNT(*) FILTER (WHERE status = 'menunggu_verifikasi')::int as menunggu, COUNT(*) FILTER (WHERE status = 'belum_bayar')::int as belum, COALESCE(SUM(nominal) FILTER (WHERE status = 'lunas'), 0)::int as total_nominal FROM iuran_bulanan WHERE tahun = ${yearInt}`;

    const summary = summaryRows[0] || { lunas: 0, menunggu: 0, belum: 0, total_nominal: 0 };

    return NextResponse.json({ data, summary });
  } catch (error) {
    console.error('Admin iuran list error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
