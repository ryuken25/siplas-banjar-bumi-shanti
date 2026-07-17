import { NextResponse } from 'next/server';
import { getSession } from '@/lib/auth';
import { sql } from '@/lib/db';

export async function GET() {
  try {
    const session = await getSession();
    if (!session) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    const data = await sql`
      SELECT * FROM notifikasi
      WHERE user_id = ${session.userId}
      ORDER BY created_at DESC
      LIMIT 50
    `;

    const unreadCount = await sql`
      SELECT COUNT(*)::int as count FROM notifikasi
      WHERE user_id = ${session.userId} AND dibaca = false
    `;

    return NextResponse.json({ data, unreadCount: unreadCount[0]?.count || 0 });
  } catch (error) {
    console.error('Notifikasi list error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}

export async function PATCH() {
  try {
    const session = await getSession();
    if (!session) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }

    await sql`UPDATE notifikasi SET dibaca = true WHERE user_id = ${session.userId} AND dibaca = false`;
    return NextResponse.json({ success: true, message: 'Semua notifikasi ditandai sudah dibaca' });
  } catch (error) {
    console.error('Notifikasi mark read error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
