"use client"
import http from "@/client/integration/http"

import ModalLoading from "@/core/components/modalLoading"
import { GetPublishedArticleResponse } from "@/server/article/_internal/type"
import { useEffect, useState } from "react"
import ArticleInfo from "../_components/ArticleInfo"
import ArticleProgressHeader from "../_components/ProcessHeader"

type Props = {
    params: Promise<{ slug: string }>
}

function ArticleDetailPage({ params }: Props) {
    const [article, setArticle] = useState<any>(null)
    const [isLoading, setIsLoading] = useState(false)

    useEffect(() => {
        async function fetchData() {
            try {
                setIsLoading(true)
                const { slug } = await params
                const response = await http.get<GetPublishedArticleResponse>(
                    `/api/public/articles/${slug}`,
                )
                const {
                    result: { article },
                } = await response.json()
                setArticle(article)
            } catch (error) {
                console.error("Error fetching data:", error)
            } finally {
                setIsLoading(false)
            }
        }
        fetchData()
    }, [])

    if (isLoading) {
        return <ModalLoading />
    }

    return (
        <div className="panel text-white border-[0.5px] border-primary/30">
            <ArticleProgressHeader
                title={article?.title}
                category={article?.category?.name || ""}
                author={article?.author?.name || ""}
            />
            <ArticleInfo
                title={article?.title}
                category={article?.category?.name || ""}
                author={article?.author?.name || ""}
                updated_at={article?.updated_at}
            />
            <div
                className="prose w-full h-full px-4"
                id="article"
                dangerouslySetInnerHTML={{ __html: article?.html }}
            />
        </div>
    )
}

export default ArticleDetailPage
