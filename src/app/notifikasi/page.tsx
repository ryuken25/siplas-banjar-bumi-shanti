'use client';

import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import { toast } from 'sonner';
import { Bell, CheckCheck, ExternalLink } from 'lucide-react';
import { cn, formatTanggal } from '@/lib/utils';

export default function NotifikasiPage() {
  const router = useRouter();
  const [data, setData] = useState<any[]>([]);
  const [unreadCount, setUnreadCount] = useState(0);
  const [loading, setLoading] = useState(true);

  useEffect(() => { fetchData(); }, []);

  async function fetchData() {
    try {
      const res = await fetch('/api/notifikasi');
      if (!res.ok) throw new Error('Gagal memuat');
      const json = await res.json();
      setData(json.data);
      setUnreadCount(json.unreadCount);
    } catch {
      toast.error('Gagal memuat notifikasi');
    } finally {
      setLoading(false);
    }
  }

  async function markAllRead() {
    try {
      const res = await fetch('/api/notifikasi', { method: 'PATCH' });
      if (!res.ok) throw new Error('Gagal');
      toast.success('Semua notifikasi ditandai sudah dibaca');
      fetchData();
    } catch {
      toast.error('Gagal menandai notifikasi');
    }
  }

  const tipeIcon: Record<string, string> = {
    laporan: '📋',
    iuran: '💰',
    akun: '👤',
    pendaftaran_baru: '📝',
  };

  return (
    <div className="p-4 sm:p-6 space-y-6">
      <div className="flex items-center justify-between">
        <div className="flex items-center gap-3">
          <h1 className="text-2xl font-bold text-gray-900">Notifikasi</h1>
          {unreadCount > 0 && (
            <span className="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-medium rounded-full">{unreadCount} baru</span>
          )}
        </div>
        {unreadCount > 0 && (
          <button onClick={markAllRead} className="flex items-center gap-1 px-3 py-2 text-sm text-green-600 hover:text-green-700 border border-green-200 rounded-lg hover:bg-green-50">
            <CheckCheck className="w-4 h-4" /> Tandai Semua Dibaca
          </button>
        )}
      </div>

      {loading ? (
        <div className="space-y-3">
          {[...Array(6)].map((_, i) => <div key={i} className="bg-white rounded-xl p-4 shadow-sm animate-pulse h-16" />)}
        </div>
      ) : data.length === 0 ? (
        <div className="text-center py-16">
          <Bell className="w-12 h-12 text-gray-300 mx-auto mb-3" />
          <p className="text-gray-500">Belum ada notifikasi</p>
        </div>
      ) : (
        <div className="space-y-2">
          {data.map((notif) => (
            <button
              key={notif.id}
              onClick={() => { if (notif.url) router.push(notif.url); }}
              className={cn(
                'w-full text-left bg-white rounded-xl p-4 shadow-sm border transition-all',
                notif.dibaca ? 'border-gray-100' : 'border-green-200 bg-green-50/30',
                notif.url && 'hover:shadow-md cursor-pointer'
              )}
            >
              <div className="flex items-start gap-3">
                <span className="text-xl mt-0.5">{tipeIcon[notif.tipe || ''] || '🔔'}</span>
                <div className="flex-1 min-w-0">
                  <div className="flex items-start justify-between gap-2">
                    <p className={cn('font-medium', notif.dibaca ? 'text-gray-700' : 'text-gray-900')}>{notif.judul}</p>
                    {!notif.dibaca && <span className="w-2 h-2 rounded-full bg-green-500 flex-shrink-0 mt-1.5" />}
                  </div>
                  {notif.pesan && <p className="text-sm text-gray-600 mt-1 line-clamp-2">{notif.pesan}</p>}
                  <div className="flex items-center gap-2 mt-2">
                    <span className="text-xs text-gray-400">{formatTanggal(notif.created_at)}</span>
                    {notif.url && <ExternalLink className="w-3 h-3 text-gray-400" />}
                  </div>
                </div>
              </div>
            </button>
          ))}
        </div>
      )}
    </div>
  );
}
