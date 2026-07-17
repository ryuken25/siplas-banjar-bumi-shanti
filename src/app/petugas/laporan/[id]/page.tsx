'use client';
import { useEffect, useState } from 'react';
import { useParams, useRouter } from 'next/navigation';
import { toast } from 'sonner';
import { STATUS_LAPORAN_LABEL, STATUS_LAPORAN_COLOR, JENIS_SAMPAH_LABEL, formatTanggal } from '@/lib/utils';
import { ArrowLeft, CheckCircle, PlayCircle, XCircle, Clock } from 'lucide-react';

export default function PetugasLaporanDetail() {
  const { id } = useParams();
  const router = useRouter();
  const [data, setData] = useState<any>(null);
  const [loading, setLoading] = useState(true);
  const [acting, setActing] = useState(false);
  const [alasan, setAlasan] = useState('');
  const [showTolak, setShowTolak] = useState(false);

  const fetchData = () => {
    fetch(`/api/petugas/laporan/${id}`).then(r => r.json()).then(d => { setData(d); setLoading(false); });
  };
  useEffect(() => { fetchData(); }, [id]);

  const doAction = async (action: string) => {
    setActing(true);
    try {
      const body: any = { action };
      if (action === 'tolak') body.alasan = alasan;
      const res = await fetch(`/api/petugas/laporan/${id}/action`, {
        method: 'POST', headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body),
      });
      const result = await res.json();
      if (result.error) { toast.error(result.error); return; }
      toast.success(`Laporan berhasil ${action === 'tolak' ? 'ditolak' : action === 'selesai' ? 'ditandai selesai' : action === 'proses' ? 'diproses' : 'diterima'}.`);
      fetchData();
      setShowTolak(false);
      setAlasan('');
    } catch { toast.error('Gagal memproses.'); }
    finally { setActing(false); }
  };

  if (loading) return <div className="animate-pulse space-y-4"><div className="h-64 bg-gray-100 rounded-xl" /><div className="h-32 bg-gray-100 rounded-xl" /></div>;
  if (!data) return <p className="text-red-500">Laporan tidak ditemukan.</p>;

  const steps = [
    { key: 'dikirim', label: 'Dikirim', icon: ArrowLeft, date: data.tanggal_lapor },
    { key: 'diterima', label: 'Diterima', icon: CheckCircle, date: data.tanggal_diterima },
    { key: 'diproses', label: 'Diproses', icon: PlayCircle, date: data.tanggal_diproses },
    { key: 'selesai', label: 'Selesai', icon: CheckCircle, date: data.tanggal_selesai },
  ];
  const currentIdx = steps.findIndex(s => s.key === data.status);

  return (
    <div className="space-y-6 animate-fade-in max-w-3xl">
      <button onClick={() => router.back()} className="flex items-center gap-2 text-gray-600 hover:text-gray-900"><ArrowLeft className="w-4 h-4" /> Kembali</button>
      <div className="bg-white rounded-xl border border-gray-100 overflow-hidden">
        {data.foto && <img src={data.foto} alt="Foto laporan" className="w-full h-64 object-cover" />}
        <div className="p-6 space-y-4">
          <div className="flex items-center justify-between">
            <h1 className="text-xl font-bold">{data.kode_laporan}</h1>
            <span className={`px-3 py-1 text-sm font-medium rounded-full ${STATUS_LAPORAN_COLOR[data.status]}`}>{STATUS_LAPORAN_LABEL[data.status]}</span>
          </div>
          <div className="grid grid-cols-2 gap-4 text-sm">
            <div><span className="text-gray-500">Jenis</span><p className="font-medium">{JENIS_SAMPAH_LABEL[data.jenis_sampah]}</p></div>
            <div><span className="text-gray-500">Pelapor</span><p className="font-medium">{data.pelapor_name || '-'}</p></div>
            <div><span className="text-gray-500">Lokasi</span><p className="font-medium">{data.lokasi_text}</p></div>
            <div><span className="text-gray-500">Tanggal</span><p className="font-medium">{formatTanggal(data.tanggal_lapor)}</p></div>
          </div>
          <div><span className="text-gray-500 text-sm">Keterangan</span><p className="mt-1">{data.keterangan}</p></div>
          {data.alasan_tolak && <div className="p-3 bg-red-50 rounded-lg text-sm text-red-700"><strong>Alasan Tolak:</strong> {data.alasan_tolak}</div>}
        </div>
      </div>

      {/* Timeline */}
      {data.status !== 'ditolak' && (
        <div className="bg-white rounded-xl border border-gray-100 p-6">
          <h2 className="font-semibold mb-4">Timeline</h2>
          <div className="flex items-center justify-between">
            {steps.map((s, i) => (
              <div key={s.key} className="flex flex-col items-center flex-1">
                <div className={`w-8 h-8 rounded-full flex items-center justify-center ${i <= currentIdx ? 'bg-brand-500 text-white' : 'bg-gray-200 text-gray-400'}`}>
                  <s.icon className="w-4 h-4" />
                </div>
                <span className="text-xs mt-1 font-medium">{s.label}</span>
                {s.date && <span className="text-xs text-gray-400">{formatTanggal(s.date)}</span>}
              </div>
            ))}
          </div>
        </div>
      )}

      {/* Actions */}
      <div className="bg-white rounded-xl border border-gray-100 p-6">
        <h2 className="font-semibold mb-4">Aksi</h2>
        <div className="flex flex-wrap gap-3">
          {data.status === 'dikirim' && (
            <>
              <button onClick={() => doAction('terima')} disabled={acting} className="px-4 py-2 bg-brand-500 text-white rounded-lg hover:bg-brand-600 disabled:opacity-50">Terima</button>
              <button onClick={() => setShowTolak(true)} className="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Tolak</button>
            </>
          )}
          {(data.status === 'dikirim' || data.status === 'diterima') && (
            <button onClick={() => doAction('proses')} disabled={acting} className="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 disabled:opacity-50">Proses</button>
          )}
          {(data.status === 'diterima' || data.status === 'diproses') && (
            <button onClick={() => doAction('selesai')} disabled={acting} className="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 disabled:opacity-50">Selesai</button>
          )}
          {data.status !== 'selesai' && data.status !== 'ditolak' && (
            <button onClick={() => setShowTolak(true)} className="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Tolak</button>
          )}
        </div>
        {showTolak && (
          <div className="mt-4 p-4 bg-red-50 rounded-lg space-y-3">
            <textarea value={alasan} onChange={e => setAlasan(e.target.value)} placeholder="Alasan penolakan..." className="w-full p-2 border rounded-lg text-sm" rows={3} />
            <div className="flex gap-2">
              <button onClick={() => doAction('tolak')} disabled={acting || alasan.length < 5} className="px-4 py-2 bg-red-500 text-white rounded-lg text-sm disabled:opacity-50">Konfirmasi Tolak</button>
              <button onClick={() => { setShowTolak(false); setAlasan(''); }} className="px-4 py-2 bg-gray-200 rounded-lg text-sm">Batal</button>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
