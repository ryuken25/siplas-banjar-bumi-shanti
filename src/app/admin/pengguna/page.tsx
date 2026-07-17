'use client';
import { Suspense } from 'react';
import { useEffect, useState } from 'react';
import { useSearchParams } from 'next/navigation';
import { toast } from 'sonner';
import { getAvatarUrl, formatTanggalShort } from '@/lib/utils';
import { CheckCircle, XCircle, UserX, UserCheck, Search } from 'lucide-react';

function PenggunaContent() {
  const searchParams = useSearchParams();
  const [tab, setTab] = useState(searchParams.get('tab') || 'pending');
  const [data, setData] = useState<any[]>([]);
  const [counts, setCounts] = useState<any>({});
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [alasan, setAlasan] = useState('');
  const [rejectId, setRejectId] = useState<string | null>(null);
  const [acting, setActing] = useState(false);

  const fetchData = () => {
    setLoading(true);
    const q = new URLSearchParams({ tab, ...(search && { search }) });
    fetch(`/api/admin/pengguna?${q}`).then(r => r.json()).then(d => {
      setData(d.data || []);
      setCounts(d.counts || {});
      setLoading(false);
    });
  };
  useEffect(() => { fetchData(); }, [tab, search]);

  const doAction = async (id: string, action: string) => {
    setActing(true);
    try {
      const body: any = { action };
      if (action === 'reject') body.alasan = alasan;
      const res = await fetch(`/api/admin/pengguna/${id}`, {
        method: 'POST', headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body),
      });
      const result = await res.json();
      if (result.error) { toast.error(result.error); return; }
      toast.success(result.message || 'Berhasil.');
      fetchData();
      setRejectId(null);
      setAlasan('');
    } catch { toast.error('Gagal.'); }
    finally { setActing(false); }
  };

  const tabs = [
    { key: 'pending', label: 'Pending', count: counts.pending },
    { key: 'aktif', label: 'Aktif', count: counts.aktif },
    { key: 'nonaktif', label: 'Nonaktif', count: counts.nonaktif },
  ];

  return (
    <div className="space-y-6 animate-fade-in">
      <h1 className="text-2xl font-bold">Kelola Pengguna</h1>
      <div className="flex gap-2">
        {tabs.map(t => (
          <button key={t.key} onClick={() => setTab(t.key)}
            className={`px-4 py-2 rounded-full text-sm font-medium ${tab === t.key ? 'bg-brand-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'}`}>
            {t.label} ({t.count || 0})
          </button>
        ))}
      </div>
      <div className="relative">
        <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
        <input value={search} onChange={e => setSearch(e.target.value)} placeholder="Cari nama, email, NIK..."
          className="w-full pl-10 pr-4 py-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500" />
      </div>
      {loading ? <div className="space-y-3">{[1,2,3].map(i => <div key={i} className="h-24 bg-gray-100 rounded-xl animate-pulse" />)}</div> : (
        <div className="space-y-3">
          {data.map((u: any) => (
            <div key={u.id} className="bg-white rounded-xl border border-gray-100 p-4 flex items-center justify-between">
              <div className="flex items-center gap-3">
                <img src={getAvatarUrl(u.name, u.foto_profil)} alt="" className="w-10 h-10 rounded-full" />
                <div>
                  <p className="font-medium text-sm">{u.name}</p>
                  <p className="text-xs text-gray-500">{u.email} · NIK: {u.nik || '-'} · {formatTanggalShort(u.created_at)}</p>
                </div>
              </div>
              <div className="flex gap-2">
                {tab === 'pending' && (
                  <>
                    <button onClick={() => doAction(u.id, 'approve')} disabled={acting} className="px-3 py-1.5 bg-brand-500 text-white rounded-lg text-xs hover:bg-brand-600 disabled:opacity-50">
                      <CheckCircle className="w-3 h-3 inline mr-1" /> Setujui
                    </button>
                    <button onClick={() => setRejectId(u.id)} className="px-3 py-1.5 bg-red-500 text-white rounded-lg text-xs hover:bg-red-600">
                      <XCircle className="w-3 h-3 inline mr-1" /> Tolak
                    </button>
                  </>
                )}
                {tab === 'aktif' && (
                  <button onClick={() => doAction(u.id, 'nonaktifkan')} disabled={acting} className="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs hover:bg-red-200 disabled:opacity-50">
                    <UserX className="w-3 h-3 inline mr-1" /> Nonaktifkan
                  </button>
                )}
                {tab === 'nonaktif' && (
                  <button onClick={() => doAction(u.id, 'aktifkan')} disabled={acting} className="px-3 py-1.5 bg-brand-100 text-brand-700 rounded-lg text-xs hover:bg-brand-200 disabled:opacity-50">
                    <UserCheck className="w-3 h-3 inline mr-1" /> Aktifkan
                  </button>
                )}
              </div>
            </div>
          ))}
          {!data.length && <p className="text-center text-gray-400 py-8">Tidak ada pengguna.</p>}
        </div>
      )}
      {rejectId && (
        <div className="fixed inset-0 bg-black/30 flex items-center justify-center z-50">
          <div className="bg-white rounded-xl p-6 w-full max-w-md space-y-4">
            <h3 className="font-semibold">Tolak Pendaftaran</h3>
            <textarea value={alasan} onChange={e => setAlasan(e.target.value)} placeholder="Alasan penolakan..." className="w-full p-3 border rounded-lg text-sm" rows={3} />
            <div className="flex gap-2 justify-end">
              <button onClick={() => { setRejectId(null); setAlasan(''); }} className="px-4 py-2 bg-gray-100 rounded-lg text-sm">Batal</button>
              <button onClick={() => doAction(rejectId, 'reject')} disabled={acting || alasan.length < 5} className="px-4 py-2 bg-red-500 text-white rounded-lg text-sm disabled:opacity-50">Tolak</button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

export default function AdminPengguna() {
  return (
    <Suspense fallback={<div className="animate-pulse space-y-4">{[1,2,3].map(i => <div key={i} className="h-24 bg-gray-100 rounded-xl" />)}</div>}>
      <PenggunaContent />
    </Suspense>
  );
}
