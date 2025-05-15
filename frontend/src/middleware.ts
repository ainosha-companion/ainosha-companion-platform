import { NextRequest, NextResponse } from "next/server"

export const config = {
    matcher: ["/((?!_next|static|favicon.ico|api|assets).*)"],
}

export async function middleware(request: NextRequest) {
    const token = request.cookies.get("x-access-token")?.value
    const path = request.nextUrl.pathname
    if (token && path === "/auth/login") {
        return NextResponse.redirect(new URL("/", request.url))
    }

    if (!token && path !== "/auth/login") {
        return NextResponse.redirect(new URL("/auth/login", request.url))
    }

    return NextResponse.next()
}
