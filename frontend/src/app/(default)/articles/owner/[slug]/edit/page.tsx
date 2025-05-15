"use client"
import { showAlert } from "@/app/_internal/utils/toast"
import http from "@/client/integration/http"

import { BtnLoading } from "@/core/components/btnLoading"
import { GetOwnerArticleResponse } from "@/server/article/_internal/type"
import dynamic from "next/dynamic"
import { useRouter } from "next/navigation"
import { KeyboardEvent, useEffect, useState } from "react"
import { FiChevronDown } from "react-icons/fi"
import { API_ENDPOINT } from "../../../_internal/endpoints"

type Props = {
    params: Promise<{ slug: string }>
}

const MarkdownEditor = dynamic(() => import("./_internal/MarkdownEditor"), {
    ssr: false,
    loading: () => <p>Loading...</p>,
})

const page = ({ params }: Props) => {
    const router = useRouter()
    const [article, setArticle] = useState<string>("")
    const [title, setTitle] = useState<string>("")
    const [tags, setTags] = useState<string[]>([])
    const [status, setStatus] = useState<string>("")
    const [id, setId] = useState<number | null>(null)
    const [inputValue, setInputValue] = useState<string>("")
    const [initialArticle, setInitialArticle] = useState<{
        title: string
        content: string
        tags: string[]
        status: string
    } | null>(null)
    const [isLoading, setIsLoading] = useState(false)

    useEffect(() => {
        async function fetchData() {
            const { slug } = await params
            try {
                const response = await http.get<GetOwnerArticleResponse>(
                    API_ENDPOINT.GetOwnerArticleBySlug(slug),
                )
                const {
                    result: { article },
                } = await response.json()
                setId(article?.id || null)
                setArticle(article?.markdown || "")
                setTitle(article?.title || "")
                setTags(article?.tags || [])
                setStatus(article?.status || "")

                setInitialArticle({
                    title: article?.title || "",
                    content: article?.markdown || "",
                    tags: article?.tags || [],
                    status: article?.status || "",
                })
            } catch (error) {
                console.error("Error fetching data:", error)
                showAlert({
                    icon: "error",
                    text: "Failed to fetch article data",
                })
                router.back()
            }
        }
        fetchData()
    }, [])

    console.log("tags", tags)

    const handleUpdateArticle = async () => {
        if (!initialArticle) return
        const updatedData: any = {}
        if (title !== initialArticle.title) updatedData.title = title
        if (article !== initialArticle.content) updatedData.content = article
        if (JSON.stringify(tags) !== JSON.stringify(initialArticle.tags))
            updatedData.tags = tags
        if (status !== initialArticle.status) updatedData.status = status
        console.log("updatedData", tags)
        if (Object.keys(updatedData).length === 0) {
            showAlert({
                icon: "info",
                text: "No changes to update",
            })
            return
        }

        try {
            setIsLoading(true)
            const response = await http
                .put(API_ENDPOINT.GetOwnerArticleBySlug(id?.toString() || ""), {
                    body: JSON.stringify(updatedData),
                    headers: { "Content-Type": "application/json" },
                })
                .json()
            if (response) {
                showAlert({
                    icon: "success",
                    title: "Success",
                    text: "Article updated successfully",
                })
                router.back()
            } else {
                showAlert({
                    icon: "error",
                    title: "Error while updating article",
                    text: "Failed to update article",
                })
            }
        } catch (error) {
            console.error("Error updating article:", error)
            showAlert({
                icon: "error",
                title: "Error while updating article",
                text: "Failed to update article",
            })
        } finally {
            setIsLoading(false)
        }
    }

    const addTag = () => {
        const trimmedTag = inputValue.trim()
        if (trimmedTag && !tags.includes(trimmedTag)) {
            setTags([...tags, trimmedTag])
            setInputValue("")
        }
    }

    const removeTag = (tag: string) => {
        setTags(tags.filter((t) => t !== tag))
    }

    const handleKeyDown = (e: KeyboardEvent<HTMLInputElement>) => {
        if (e.key === "Enter" || e.key === " ") {
            e.preventDefault()
            addTag()
        }
    }

    return (
        <div className="mx-auto overflow-hidden rounded-md bg-gray-900 shadow-2xl">
            {/* Header with glass effect */}
            <div className=" backdrop-blur-sm border-b border-gray-700/50 px-6 py-2 flex items-center justify-between">
                {/* Left side: Back button + Title */}
                <div className="flex items-center gap-2 text-white text-base sm:text-lg font-bold">
                    <span>Edit Article</span>
                </div>

                {/* Right side: Save button */}
                <button
                    onClick={handleUpdateArticle}
                    className="rounded-lg bg-gradient-to-r from-primary to-gray-300 px-5 py-2 text-sm text-black font-bold transition shadow-lg"
                >
                    {isLoading ? <BtnLoading /> : "Save"}
                </button>
            </div>

            <div className="px-6 py-6 space-y-6">
                {/* Title Field with improved styling */}
                <div>
                    <label className="mb-2 block text-sm font-medium text-white">
                        Title
                    </label>
                    <input
                        type="text"
                        value={title}
                        onChange={(e) => setTitle(e.target.value)}
                        className="w-full rounded-lg border border-gray-700 bg-gray-800/50 px-4 py-3 text-white placeholder-gray-500 focus:border-primary/80 focus:outline-none focus:ring-1 transition-colors"
                        placeholder="Enter article title"
                    />
                </div>
                {/* Status Field */}
                <div>
                    <label className="mb-2 block text-sm font-medium text-white">
                        Status
                    </label>

                    <div className="relative">
                        <select
                            value={status}
                            onChange={(e) => setStatus(e.target.value)}
                            className="appearance-none w-full rounded-lg border border-gray-700 bg-gray-800/50 px-4 py-3 pr-10 text-white placeholder-gray-500 focus:border-primary/80 focus:outline-none focus:ring-1 transition-colors"
                        >
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>

                        <FiChevronDown className="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-white" />
                    </div>
                </div>

                {/* Tags Field */}
                <div className="w-full mx-auto bg-gray-900 text-white rounded-lg">
                    <div className="mb-2 text-sm font-medium">Tags</div>
                    <div className="flex items-center flex-wrap gap-2 bg-gray-800 border border-gray-700 rounded-md p-2">
                        {tags.map((tag) => (
                            <span
                                key={tag}
                                className="flex items-center bg-primary/20 text-primary border-primary/30 px-2 py-1 rounded-full text-sm"
                            >
                                {tag}
                                <button
                                    onClick={() => removeTag(tag)}
                                    className="ml-1 text-primary hover:text-red-500"
                                >
                                    ×
                                </button>
                            </span>
                        ))}
                        <input
                            type="text"
                            value={inputValue}
                            onChange={(e) => setInputValue(e.target.value)}
                            onKeyDown={handleKeyDown}
                            placeholder="Add tag..."
                            className="flex-1 min-w-[100px] bg-transparent outline-none text-white placeholder-gray-500"
                        />
                    </div>
                </div>

                {/* Markdown Editor */}
                <div>
                    <label className="mb-2 block text-sm font-medium text-white">
                        Content
                    </label>
                    <MarkdownEditor
                        content={article}
                        onChange={setArticle}
                        classname="min-h-[500px] rounded-xl border border-gray-700 bg-gray-800/30 p-4 shadow-inner backdrop-blur-sm"
                    />
                </div>
            </div>
        </div>
    )
}

export default page
