import {
    deleteArticle,
    GetOwnerArticleBySlug,
    updateArticleBySlug,
} from "@/server/article/api"

export async function GET(
    request: Request,
    { params }: { params: Promise<{ id: string }> },
) {
    try {
        const { id } = await params
        const response = await GetOwnerArticleBySlug(id)
        return Response.json(response)
    } catch (error: any) {
        throw new Error(`Failed to fetch article: ${error}`)
    }
}

export async function PUT(
    request: Request,
    { params }: { params: Promise<{ id: string }> },
): Promise<Response> {
    try {
        const { id } = await params
        const body = await request.json()
        const response = await updateArticleBySlug(id, body)
        return Response.json(response)
    } catch (error) {
        throw new Error(`Failed to update article: ${error}`)
    }
}

export async function DELETE(
    request: Request,
    { params }: { params: Promise<{ id: string }> },
) {
    try {
        const { id } = await params
        const response = await deleteArticle(id)
        return Response.json(response)
    } catch (error) {
        throw new Error(`Failed to delete article: ${error}`)
    }
}
