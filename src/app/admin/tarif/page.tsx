'use client';

import { useEffect, useState } from 'react';
import { toast } from 'sonner';
import { Plus, Pencil, Trash2, X, Check } from 'lucide-react';
import { cn, formatRupiah, formatTanggalShort } from '@/lib/utils';

export default function AdminTarifPage() {
  const [data, setData] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [editId, setEditId] = useState<number | null>(null);
  const [form, setForm] = useState({ nominal: '', periode_mulai: '', keterangan: '', aktif: true });
  const [formLoading, setFormLoading] = useState(false);

  useEffect(() => { fetchData(); }, []);

  async function fetchData() {
    setLoading(true);
    try {
      const res = await fetch('/api/admin/tarif');
      if (!res.ok) throw new Error('Gagal memuat');
      const json = await res.json();
      setData(json.data);
    } catch {
      toast.error('Gagal memuat data tarif');
    } finally {
      setLoading(false);
    }
  }

  function openAdd() {
    setEditId(null);
    setForm({ nominal: '', periode_mulai: '', keterangan: '', aktif: true });
    setShowModal(true);
  }

  function openEdit(tarif: any) {
    setEditId(tarif.id);
    setForm({
      nominal: String(tarif.nominal),
      periode_mulai: tarif.periode_mulai,
      keterangan: tarif.keterangan || '',
      aktif: tarif.aktif,
    });
    setShowModal(true);
  }

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    setFormLoading(true);
    try {
      const body = {
        nominal: parseInt(form.nominal),
        periode_mulai: form.periode_mulai,
        keterangan: form.keterangan || null,
        aktif: form.aktif,
      };

      if (editId) {
        const res = await fetch(`/api/admin/tarif/${editId}`, {
          method: 'PATCH',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(body),
        });
        const json = await res.json();
        if (!res.ok) throw new Error(json.error);
        toast.success(json.message);
      } else {
        if (!form.nominal || !form.periode_mulai) {
          toast.error('Nominal dan periode wajib diisi');
          return;
        }
        const res = await fetch('/api/admin/tarif', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(body),
        });
        const json = await res.json();
        if (!res.ok) throw new Error(json.error);
        toast.success(json.message);
      }
      setShowModal(false);
      fetchData();
    } catch (err: any) {
      toast.error(err.message || 'Gagal menyimpan');
    } finally {
      setFormLoading(false);
    }
  }

  async function handleDelete(id: number) {
    if (!confirm('Hapus tarif ini?')) return;
    try {
      const res = await fetch(`/api/admin/tarif/${id}`, { method: 'DELETE' });
      const json = await res.json();
      if (!res.ok) throw new Error(json.error);
      toast.success(json.message);
      fetchData();
    } catch (err: any) {
      toast.error(err.message || 'Gagal menghapus');
    }
  }

  return (
    <div className="p-4 sm:p-6 space-y-6">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-bold text-gray-900">Kelola Tarif Iuran</h1>
        <button onClick={openAdd} className="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
          <Plus className="w-4 h-4" /> Tambah Tarif
        </button>
      </div>

      {loading ? (
        <div className="space-y-3">
          {[...Array(4)].map((_, i) => <div key={i} className="bg-white rounded-xl p-5 shadow-sm animate-pulse h-20" />)}
        </div>
      ) : data.length === 0 ? (
        <div className="text-center py-12 text-gray-500">Belum ada tarif. Tambahkan tarif pertama Anda.</div>
      ) : (
        <div className="space-y-3">
          {data.map((tarif) => (
            <div key={tarif.id} className="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <div className="flex-1">
                <div className="flex items-center gap-3 mb-1">
                  <p className="text-xl font-bold text-green-600">{formatRupiah(tarif.nominal)}</p>
                  {tarif.aktif && (
                    <span className="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700 border border-green-200 flex items-center gap-1">
                      <Check className="w-3 h-3" /> Aktif
                    </span>
                  )}
                </div>
                <p className="text-sm text-gray-600">Periode mulai: {formatTanggalShort(tarif.periode_mulai)}</p>
                {tarif.keterangan && <p className="text-sm text-gray-500 mt-1">{tarif.keterangan}</p>}
              </div>
              <div className="flex gap-2">
                <button onClick={() => openEdit(tarif)} className="flex items-center gap-1 px-3 py-2 border border-gray-200 rounded-lg text-sm hover:bg-gray-50">
                  <Pencil className="w-3.5 h-3.5" /> Edit
                </button>
                <button onClick={() => handleDelete(tarif.id)} className="flex items-center gap-1 px-3 py-2 border border-red-200 text-red-600 rounded-lg text-sm hover:bg-red-50">
                  <Trash2 className="w-3.5 h-3.5" /> Hapus
                </button>
              </div>
            </div>
          ))}
        </div>
      )}

      {/* Modal */}
      {showModal && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
          <div className="bg-white rounded-xl p-6 w-full max-w-md">
            <div className="flex items-center justify-between mb-4">
              <h3 className="text-lg font-semibold">{editId ? 'Edit Tarif' : 'Tambah Tarif'}</h3>
              <button onClick={() => setShowModal(false)}><X className="w-5 h-5 text-gray-400" /></button>
            </div>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Nominal (Rp)</label>
                <input type="number" value={form.nominal} onChange={(e) => setForm({ ...form, nominal: e.target.value })} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent" required min="0" />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Periode Mulai</label>
                <input type="date" value={form.periode_mulai} onChange={(e) => setForm({ ...form, periode_mulai: e.target.value })} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent" required />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Keterangan (opsional)</label>
                <input type="text" value={form.keterangan} onChange={(e) => setForm({ ...form, keterangan: e.target.value })} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent" />
              </div>
              <label className="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" checked={form.aktif} onChange={(e) => setForm({ ...form, aktif: e.target.checked })} className="w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-green-500" />
                <span className="text-sm text-gray-700">Jadikan tarif aktif</span>
              </label>
              <div className="flex gap-2 pt-2">
                <button type="button" onClick={() => setShowModal(false)} className="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm hover:bg-gray-50">Batal</button>
                <button type="submit" disabled={formLoading} className="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 disabled:opacity-50">
                  {formLoading ? 'Menyimpan...' : editId ? 'Simpan' : 'Tambah'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
