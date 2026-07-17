export interface User {
  id: string;
  name: string;
  email: string;
  nik: string | null;
  no_kk: string | null;
  no_telp: string | null;
  alamat: string | null;
  foto_profil: string | null;
  role: 'admin' | 'petugas' | 'warga';
  status_akun: 'pending' | 'aktif' | 'nonaktif';
  alasan_tolak_akun: string | null;
  created_at: string;
  updated_at: string;
}

export interface LaporanSampah {
  id: number;
  kode_laporan: string;
  user_id: string;
  jenis_sampah: 'organik' | 'anorganik' | 'b3' | 'campuran';
  lokasi_text: string;
  latitude: number | null;
  longitude: number | null;
  keterangan: string;
  foto: string;
  status: 'dikirim' | 'diterima' | 'diproses' | 'selesai' | 'ditolak';
  petugas_id: string | null;
  alasan_tolak: string | null;
  tanggal_lapor: string;
  tanggal_diterima: string | null;
  tanggal_diproses: string | null;
  tanggal_selesai: string | null;
  created_at: string;
  updated_at: string;
  // Joined fields
  pelapor_name?: string;
  petugas_name?: string;
}

export interface IuranBulanan {
  id: number;
  kode_tagihan: string;
  user_id: string;
  bulan: number;
  tahun: number;
  nominal: number;
  status: 'belum_bayar' | 'menunggu_verifikasi' | 'lunas' | 'ditolak';
  metode_bayar: 'transfer' | 'tunai' | null;
  bukti_bayar: string | null;
  tanggal_bayar: string | null;
  verifikator_id: string | null;
  tanggal_verifikasi: string | null;
  alasan_tolak: string | null;
  created_at: string;
  updated_at: string;
  // Joined fields
  warga_name?: string;
  verifikator_name?: string;
}

export interface TarifIuran {
  id: number;
  nominal: number;
  periode_mulai: string;
  keterangan: string | null;
  aktif: boolean;
  created_at: string;
  updated_at: string;
}

export interface Notifikasi {
  id: string;
  user_id: string;
  judul: string;
  pesan: string | null;
  tipe: string | null;
  data: any;
  url: string | null;
  dibaca: boolean;
  created_at: string;
}
