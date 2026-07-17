import { NextResponse } from 'next/server';
import { getSession } from '@/lib/auth';
import { sql } from '@/lib/db';

export async function PATCH(request: Request, { params }: { params: { id: string } }) {
  try {
    const session = await getSession();
    if (!session || session.role !== 'admin') {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const { id } = params;
    const body = await request.json();
    const { nominal, periode_mulai, keterangan, aktif } = body;

    const existing = await sql`SELECT * FROM tarif_iuran WHERE id = ${id}`;
    if (existing.length === 0) {
      return NextResponse.json({ error: 'Tarif tidak ditemukan' }, { status: 404 });
    }

    if (aktif) {
      await sql`UPDATE tarif_iuran SET aktif = false, updated_at = NOW() WHERE aktif = true AND id != ${id}`;
    }

    const now = new Date().toISOString();
    const updates: string[] = ['updated_at = $1'];
    const values: any[] = [now];
    let idx = 2;

    if (nominal !== undefined) { updates.push(`nominal = $${idx++}`); values.push(nominal); }
    if (periode_mulai) { updates.push(`periode_mulai = $${idx++}`); values.push(periode_mulai); }
    if (keterangan !== undefined) { updates.push(`keterangan = $${idx++}`); values.push(keterangan); }
    if (aktif !== undefined) { updates.push(`aktif = $${idx++}`); values.push(aktif); }
    values.push(id);

    await sql(`UPDATE tarif_iuran SET ${updates.join(', ')} WHERE id = $${idx}`, values);

    const updated = await sql`SELECT * FROM tarif_iuran WHERE id = ${id}`;
    return NextResponse.json({ data: updated[0], message: 'Tarif berhasil diperbarui' });
  } catch (error) {
    console.error('Admin tarif update error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}

export async function DELETE(request: Request, { params }: { params: { id: string } }) {
  try {
    const session = await getSession();
    if (!session || session.role !== 'admin') {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const { id } = params;
    const existing = await sql`SELECT * FROM tarif_iuran WHERE id = ${id}`;
    if (existing.length === 0) {
      return NextResponse.json({ error: 'Tarif tidak ditemukan' }, { status: 404 });
    }

    await sql`DELETE FROM tarif_iuran WHERE id = ${id}`;
    return NextResponse.json({ success: true, message: 'Tarif berhasil dihapus' });
  } catch (error) {
    console.error('Admin tarif delete error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
