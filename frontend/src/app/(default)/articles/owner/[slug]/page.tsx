"use client"
import http from "@/client/integration/http"
import ModalLoading from "@/core/components/modalLoading"
import { GetOwnerArticleResponse } from "@/server/article/_internal/type"
import { useEffect, useState } from "react"
import ArticleInfo from "../../_components/ArticleInfo"
import ArticleProgressHeader from "../../_components/ProcessHeader"
import { API_ENDPOINT } from "../../_internal/endpoints"

type Props = {
    params: Promise<{ slug: string }>
}

const ArticlePage = ({ params }: Props) => {
    const [article, setArticle] = useState<any>(null)
    const [isLoading, setIsLoading] = useState(false)
    useEffect(() => {
        async function fetchData() {
            try {
                setIsLoading(true)
                const { slug } = await params
                const response = await http.get<GetOwnerArticleResponse>(
                    API_ENDPOINT.GetOwnerArticleBySlug(slug),
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
        <div className="panel border-[0.5px]  border-primary/30">
            <ArticleProgressHeader
                title={article?.title}
                category={article?.category.name || []}
                author={article?.author.name}
            />
            <ArticleInfo
                title={article?.title}
                category={article?.category.name || []}
                author={article?.author.name}
                updated_at={article?.updated_at}
            />
            {article?.html && (
                <div
                    className="prose w-full h-full px-4"
                    id="article"
                    dangerouslySetInnerHTML={{
                        __html: article?.html || "<p>No content yet</p>",
                    }}
                />
            )}
        </div>
    )
}

export default ArticlePage
