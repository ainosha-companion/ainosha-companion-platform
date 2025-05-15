import { logout } from "@/server/auth/api"
import { removeAccessToken } from "@/server/token"

export async function POST() {
    try {
        const response = await logout()
        if (response) {
            await removeAccessToken()
        }
        return Response.json({})
    } catch (error) {
        console.log(error)
        return Response.json({})
    }
}
