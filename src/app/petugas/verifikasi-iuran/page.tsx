'use client';
import { useEffect, useState } from 'react';
import { toast } from 'sonner';
import { formatRupiah, BULAN_LABEL, formatTanggal } from '@/lib/utils';
import { CheckCircle, XCircle, Eye } from 'lucide-react';

export default function VerifikasiIuran() {
  const [data, setData] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [alasan, setAlasan] = useState('');
  const [tolakId, setTolakId] = useState<number | null>(null);
  const [acting, setActing] = useState(false);

  const fetchData = () => {
    fetch('/api/petugas/iuran').then(r => r.json()).then(d => { setData(d.data || []); setLoading(false); });
  };
  useEffect(() => { fetchData(); }, []);

  const doAction = async (id: number, action: string) => {
    setActing(true);
    try {
      const body: any = { action };
      if (action === 'tolak') body.alasan = alasan;
      const res = await fetch(`/api/petugas/iuran/${id}/action`, {
        method: 'POST', headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body),
      });
      const result = await res.json();
      if (result.error) { toast.error(result.error); return; }
      toast.success(action === 'setujui' ? 'Pembayaran disetujui.' : 'Pembayaran ditolak.');
      fetchData();
      setTolakId(null);
      setAlasan('');
    } catch { toast.error('Gagal memproses.'); }
    finally { setActing(false); }
  };

  if (loading) return <div className="animate-pulse space-y-4">{[1,2,3].map(i => <div key={i} className="h-32 bg-gray-100 rounded-xl" />)}</div>;

  return (
    <div className="space-y-6 animate-fade-in">
      <h1 className="text-2xl font-bold">Verifikasi Iuran</h1>
      {!data.length ? (
        <div className="text-center py-12 text-gray-400"><CheckCircle className="w-12 h-12 mx-auto mb-2 opacity-50" /><p>Tidak ada pembayaran menunggu verifikasi.</p></div>
      ) : (
        <div className="space-y-4">
          {data.map((i: any) => (
            <div key={i.id} className="bg-white rounded-xl border border-gray-100 p-5">
              <div className="flex items-start justify-between mb-3">
                <div>
                  <p className="font-semibold">{i.warga_name || 'Warga'}</p>
                  <p className="text-sm text-gray-500">{BULAN_LABEL[i.bulan]} {i.tahun} — {formatRupiah(i.nominal)}</p>
                  <p className="text-xs text-gray-400 mt-1">Dibayar: {formatTanggal(i.tanggal_bayar)} · {i.metode_bayar}</p>
                </div>
                <span className="px-2 py-1 text-xs bg-amber-100 text-amber-700 rounded-full">Menunggu</span>
              </div>
              {i.bukti_bayar && (
                <a href={i.bukti_bayar} target="_blank" rel="noopener noreferrer" className="inline-flex items-center gap-1 text-sm text-blue-600 hover:underline mb-3">
                  <Eye className="w-4 h-4" /> Lihat Bukti Bayar
                </a>
              )}
              <div className="flex gap-2 mt-2">
                <button onClick={() => doAction(i.id, 'setujui')} disabled={acting} className="px-4 py-2 bg-brand-500 text-white rounded-lg text-sm hover:bg-brand-600 disabled:opacity-50">
                  <CheckCircle className="w-4 h-4 inline mr-1" /> Setujui
                </button>
                <button onClick={() => setTolakId(i.id)} className="px-4 py-2 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600">
                  <XCircle className="w-4 h-4 inline mr-1" /> Tolak
                </button>
              </div>
              {tolakId === i.id && (
                <div className="mt-3 p-3 bg-red-50 rounded-lg space-y-2">
                  <textarea value={alasan} onChange={e => setAlasan(e.target.value)} placeholder="Alasan penolakan..." className="w-full p-2 border rounded text-sm" rows={2} />
                  <div className="flex gap-2">
                    <button onClick={() => doAction(i.id, 'tolak')} disabled={acting || alasan.length < 5} className="px-3 py-1.5 bg-red-500 text-white rounded text-sm disabled:opacity-50">Konfirmasi</button>
                    <button onClick={() => { setTolakId(null); setAlasan(''); }} className="px-3 py-1.5 bg-gray-200 rounded text-sm">Batal</button>
                  </div>
                </div>
              )}
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
