import http from "@/server/integration/http"
import * as v from "valibot"
import { HTTPEndpoint } from "../_internal/enpoints"
import { ArticleRequestSchema } from "./_internal/schema"
import { ArticleRequest } from "./_internal/type"

export async function articleRequest(payload: ArticleRequest) {
    try {
        const validated = v.parse(ArticleRequestSchema, payload)
        const response = await http
            .post(HTTPEndpoint.ArticleRequest, {
                body: JSON.stringify(validated),
            })
            .json()
        return response
    } catch (error: any) {
        throw new Error(error)
    }
}
