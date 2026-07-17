import { NextResponse } from 'next/server';
import { getSession } from '@/lib/auth';
import { sql } from '@/lib/db';
import { BULAN_LABEL } from '@/lib/utils';

export async function POST(request: Request, { params }: { params: { id: string } }) {
  try {
    const session = await getSession();
    if (!session || session.role !== 'petugas') {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const { id } = params;
    const body = await request.json();
    const { action, alasan } = body;

    if (!['setujui', 'tolak'].includes(action)) {
      return NextResponse.json({ error: 'Aksi tidak valid' }, { status: 400 });
    }

    const iuran = await sql`SELECT * FROM iuran_bulanan WHERE id = ${id}`;
    if (iuran.length === 0) {
      return NextResponse.json({ error: 'Tagihan tidak ditemukan' }, { status: 404 });
    }

    const current = iuran[0];
    if (current.status !== 'menunggu_verifikasi') {
      return NextResponse.json({ error: 'Tagihan belum menunggu verifikasi' }, { status: 400 });
    }

    const now = new Date().toISOString();
    let notifJudul = '';
    let notifPesan = '';
    const periode = `${BULAN_LABEL[current.bulan as keyof typeof BULAN_LABEL]} ${current.tahun}`;

    if (action === 'setujui') {
      await sql`
        UPDATE iuran_bulanan SET status = 'lunas', verifikator_id = ${session.userId}, tanggal_verifikasi = ${now}, updated_at = ${now}
        WHERE id = ${id}
      `;
      notifJudul = 'Pembayaran Iuran Disetujui';
      notifPesan = `Pembayaran iuran ${periode} sebesar Rp ${current.nominal.toLocaleString('id-ID')} telah diverifikasi dan disetujui. Terima kasih!`;
    } else {
      if (!alasan) {
        return NextResponse.json({ error: 'Alasan penolakan wajib diisi' }, { status: 400 });
      }
      await sql`
        UPDATE iuran_bulanan SET status = 'ditolak', verifikator_id = ${session.userId}, tanggal_verifikasi = ${now}, alasan_tolak = ${alasan}, updated_at = ${now}
        WHERE id = ${id}
      `;
      notifJudul = 'Pembayaran Iuran Ditolak';
      notifPesan = `Pembayaran iuran ${periode} ditolak. Alasan: ${alasan}. Silakan upload ulang bukti bayar yang benar.`;
    }

    // Create notifikasi
    await sql`
      INSERT INTO notifikasi (user_id, judul, pesan, tipe, url)
      VALUES (${current.user_id}, ${notifJudul}, ${notifPesan}, 'iuran', ${'/warga/iuran'})
    `;

    return NextResponse.json({ success: true, message: `Iuran berhasil di${action === 'setujui' ? 'setujui' : 'tolak'}` });
  } catch (error) {
    console.error('Petugas iuran action error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
