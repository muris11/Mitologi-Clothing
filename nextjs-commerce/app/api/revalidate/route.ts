import { revalidatePath } from "next/cache";
import { NextRequest, NextResponse } from "next/server";

/**
 * On-demand revalidation endpoint called by the Laravel backend
 * after admin CRUD operations (create/update/delete).
 *
 * Usage: POST /api/revalidate?secret=<REVALIDATION_SECRET>
 * Body:  { "tags": ["products", "categories", ...] }
 */
export async function POST(req: NextRequest): Promise<NextResponse> {
  const secret = req.nextUrl.searchParams.get("secret");

  if (!secret || secret !== process.env.REVALIDATION_SECRET) {
    return NextResponse.json({ message: "Invalid secret" }, { status: 401 });
  }

  try {
    const body = await req.json().catch(() => ({}));
    const tags: string[] = body.tags || [];

    if (tags.length === 0) {
      return NextResponse.json({ message: "No tags provided" }, { status: 400 });
    }

    for (const tag of tags) {
      if (tag === 'products') {
        revalidatePath('/shop', 'page');
        revalidatePath('/', 'page');
      } else {
        revalidatePath('/', 'layout'); // clear all built pages
      }
    }

    return NextResponse.json({
      revalidated: true,
      tags,
      now: Date.now(),
    });
  } catch {
    return NextResponse.json({ message: "Error processing request" }, { status: 500 });
  }
}
