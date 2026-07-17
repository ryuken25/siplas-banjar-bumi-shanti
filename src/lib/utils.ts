import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
}

export function formatRupiah(num: number | null | undefined): string {
  if (num === null || num === undefined) return 'Rp 0';
  return 'Rp ' + num.toLocaleString('id-ID');
}

export function formatTanggal(date: string | Date | null | undefined): string {
  if (!date) return '-';
  const d = typeof date === 'string' ? new Date(date) : date;
  return d.toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
}

export function formatTanggalShort(date: string | Date | null | undefined): string {
  if (!date) return '-';
  const d = typeof date === 'string' ? new Date(date) : date;
  return d.toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
  });
}

export const BULAN_LABEL: Record<number, string> = {
  1: 'Januari', 2: 'Februari', 3: 'Maret', 4: 'April',
  5: 'Mei', 6: 'Juni', 7: 'Juli', 8: 'Agustus',
  9: 'September', 10: 'Oktober', 11: 'November', 12: 'Desember',
};

export const STATUS_LAPORAN_LABEL: Record<string, string> = {
  dikirim: 'Dikirim',
  diterima: 'Diterima',
  diproses: 'Diproses',
  selesai: 'Selesai',
  ditolak: 'Ditolak',
};

export const STATUS_LAPORAN_COLOR: Record<string, string> = {
  dikirim: 'bg-blue-100 text-blue-800 border-blue-200',
  diterima: 'bg-amber-100 text-amber-800 border-amber-200',
  diproses: 'bg-purple-100 text-purple-800 border-purple-200',
  selesai: 'bg-green-100 text-green-800 border-green-200',
  ditolak: 'bg-red-100 text-red-800 border-red-200',
};

export const STATUS_IURAN_LABEL: Record<string, string> = {
  belum_bayar: 'Belum Bayar',
  menunggu_verifikasi: 'Menunggu Verifikasi',
  lunas: 'Lunas',
  ditolak: 'Ditolak',
};

export const STATUS_IURAN_COLOR: Record<string, string> = {
  belum_bayar: 'bg-red-100 text-red-800 border-red-200',
  menunggu_verifikasi: 'bg-amber-100 text-amber-800 border-amber-200',
  lunas: 'bg-green-100 text-green-800 border-green-200',
  ditolak: 'bg-red-100 text-red-800 border-red-200',
};

export const JENIS_SAMPAH_LABEL: Record<string, string> = {
  organik: 'Organik',
  anorganik: 'Anorganik',
  b3: 'Bahan Berbahaya & Beracun (B3)',
  campuran: 'Campuran',
};

export const JENIS_SAMPAH_ICON: Record<string, string> = {
  organik: '🥗',
  anorganik: '♻️',
  b3: '☣️',
  campuran: '🗑️',
};

export function getInisial(name: string): string {
  const parts = name.trim().split(/\s+/);
  const first = parts[0]?.[0] || '';
  const last = parts.length > 1 ? parts[parts.length - 1][0] : '';
  return (first + last).toUpperCase();
}

export function getAvatarColor(name: string): string {
  const palette = ['#10B981', '#059669', '#34D399', '#F59E0B', '#0EA5E9', '#6366F1', '#EC4899', '#14B8A6'];
  let hash = 0;
  for (let i = 0; i < (name || 'U').length; i++) {
    hash = name.charCodeAt(i) + ((hash << 5) - hash);
  }
  return palette[Math.abs(hash) % palette.length];
}

export function getAvatarUrl(name: string, fotoProfil?: string | null): string {
  if (fotoProfil) return fotoProfil;
  const initials = encodeURIComponent(getInisial(name) || 'U');
  const color = getAvatarColor(name).replace('#', '');
  return `https://ui-avatars.com/api/?name=${initials}&background=${color}&color=ffffff&bold=true&format=svg`;
}
