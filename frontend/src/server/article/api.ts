import * as v from "valibot"
import http from "../integration/http"
import { HTTPEndpoint } from "./_internal/enpoints"
import {
    ArticleListSchema,
    GetOwnerArticleBySlugSchema,
    GetPublishedArticleSchema,
    GetPublishedArticlesSchema,
} from "./_internal/schema"
import {
    GetArticlesResponse,
    GetOwnerArticleResponse,
    GetPublishedArticleResponse,
} from "./_internal/type"

export async function getAllArticles(
    searchParams: string,
): Promise<GetArticlesResponse> {
    try {
        const response = await http
            .get(
                searchParams
                    ? `${HTTPEndpoint.GetArticlesAdmin}?${searchParams}`
                    : HTTPEndpoint.GetArticlesAdmin,
            )
            .json()

        const validated = v.parse(ArticleListSchema, response)
        return validated
    } catch (error: any) {
        throw new Error(error)
    }
}

async function getPublishedArticles(searchParams: string) {
    const response = await http
        .get(HTTPEndpoint.GetPublishedArticles(searchParams))
        .json()

    const validated = v.parse(GetPublishedArticlesSchema, response)
    return validated
}

async function getPublishedArticleBySlug(id: string) {
    const response = await http
        .get<GetPublishedArticleResponse>(HTTPEndpoint.PublicArticleBySlug(id))
        .json()
    const validated = v.parse(GetPublishedArticleSchema, response)
    return validated
}

const GetOwnerArticleBySlug = async (slug: string) => {
    const response = await http
        .get<GetOwnerArticleResponse>(HTTPEndpoint.GetOwnerArticleBySlug(slug))
        .json()

    const validated = v.parse(GetOwnerArticleBySlugSchema, response)
    return validated
}

async function deleteArticle(slug: string) {
    try {
        const response = await http
            .delete(HTTPEndpoint.DeleteArticle(slug))
            .json()
        console.log("response", response)
        return response
    } catch (error: any) {
        throw new Error(error)
    }
}

async function updateArticleBySlug(id: string, body: any) {
    try {
        const response = await http
            .put(HTTPEndpoint.UpdateArticle(id), {
                body: JSON.stringify(body),
            })
            .json()
        return response
    } catch (error: any) {
        throw new Error(error)
    }
}

export {
    deleteArticle,
    GetOwnerArticleBySlug,
    getPublishedArticleBySlug,
    getPublishedArticles,
    updateArticleBySlug,
}
