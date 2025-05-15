"use client"

import { showAlert } from "@/app/_internal/utils/toast"
import http from "@/client/integration/http"
import ConfirmDeleteModal from "@/core/components/confirmModal"
import Image from "next/image"
import Link from "next/link"
import { useRef, useState } from "react"
import {
    FaFolderOpen,
    FaNewspaper,
    FaRegClock,
    FaUserPen,
} from "react-icons/fa6"
import { RiDeleteBinLine, RiEditLine } from "react-icons/ri"
import { API_ENDPOINT } from "../_internal/endpoints"
import { ArticleListItem } from "../_internal/type"
type Props = {
    articles: ArticleListItem[]
}

export default function ArticleTable({ articles }: Props) {
    const [isOpen, setIsOpen] = useState(false)
    const [isLoading, setIsLoading] = useState(false)
    const [articlesList, setArticles] = useState<ArticleListItem[]>(articles)
    const idRef = useRef<string>("")
    const handleDelete = async (id: string) => {
        try {
            setIsLoading(true)
            await http
                .delete(API_ENDPOINT.DeleteArticle(id), { retry: 0 })
                .json()
            showAlert({
                icon: "success",
                title: "Article deleted successfully.",
            })
            setArticles((prev) =>
                prev.filter((article) => article.id.toString() !== id),
            )
        } catch (error: any) {
            console.log("error", error)
            showAlert({
                icon: "error",
                title: "Failed to delete article.",
            })
        } finally {
            setIsLoading(false)
        }
    }
    return (
        <div className="overflow-x-auto rounded-2xl border border-gray-800 bg-gradient-to-br from-gray-900 to-gray-950 shadow-xl">
            <div className="min-w-full divide-y divide-gray-800">
                {/* Header */}
                <div className="grid grid-cols-12 lg:grid-cols-12 md:grid-cols-8 sm:grid-cols-4 items-center bg-gray-900/90 px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-400 backdrop-blur">
                    <div className="col-span-5 flex items-center gap-2">
                        <FaNewspaper className="text-primary" />
                        <span>Article</span>
                    </div>
                    <div className="col-span-2 hidden md:flex items-center gap-2 justify-center">
                        <FaFolderOpen className="text-primary" />
                        <span>Category</span>
                    </div>
                    <div className="col-span-1 hidden lg:flex items-center gap-2">
                        <FaRegClock className="text-primary" />
                        <span>Date</span>
                    </div>
                    <div className="col-span-1 hidden lg:flex items-center gap-2">
                        <FaRegClock className="text-primary" />
                        <span>Status</span>
                    </div>
                    <div className="col-span-1 hidden md:flex items-center gap-2">
                        <FaUserPen className="text-primary" />
                        <span>Author</span>
                    </div>
                    <div className="col-span-2 flex items-center justify-center gap-2">
                        <FaUserPen className="hidden md:block text-primary" />
                        <span className="hidden md:inline">Actions</span>
                    </div>
                </div>

                {/* Rows */}
                {articlesList.map((article) => (
                    <div
                        key={article.id}
                        className="group grid grid-cols-12 lg:grid-cols-12 md:grid-cols-8 sm:grid-cols-4 items-center px-6 py-5 text-sm text-gray-300 transition hover:bg-gray-800/50 gap-2"
                    >
                        {/* Thumbnail + Title */}
                        <div className="col-span-5 flex items-center gap-4">
                            <div className="relative h-12 w-12 flex-shrink-0 overflow-hidden rounded-lg bg-gray-700 shadow-inner">
                                <Image
                                    src={
                                        article.thumbnail ||
                                        "/assets/images/img-articles.jpg"
                                    }
                                    alt={article.title}
                                    fill
                                    className="object-cover"
                                />
                            </div>
                            <div className="flex-1 min-w-0">
                                <Link
                                    href={`/articles/owner/${article.slug}`}
                                    className="line-clamp-2 font-medium text-white hover:underline mr-4"
                                >
                                    {article.title}
                                </Link>
                            </div>
                        </div>

                        {/* Category */}
                        <div className="col-span-2 text-xs text-gray-400 italic hidden md:block text-center">
                            {article?.category?.name || "Uncategorized"}
                        </div>

                        {/* Date */}
                        <div className="col-span-1 text-xs text-gray-400 hidden lg:block">
                            {article.updated_at
                                ? new Date(
                                      article.updated_at,
                                  ).toLocaleDateString()
                                : "N/A"}
                        </div>

                        {/* Status */}
                        <div className="col-span-1 text-xs text-gray-400 capitalize hidden lg:block text-center">
                            {article.status}
                        </div>

                        {/* Author */}
                        <div className="col-span-2 text-xs text-gray-400 italic hidden md:block ">
                            {article.author?.name || "Unknown"}
                        </div>

                        {/* Actions */}
                        <div className="col-span-1 flex justify-end gap-2 text-sm">
                            <Link
                                href={`/articles/owner/${article.slug}/edit`}
                                className="flex items-end md:items-center gap-1 rounded-md px-2 py-1 text-primary/80 hover:bg-primary/10 transition"
                            >
                                <RiEditLine className="text-lg" />
                                <span className="hidden md:inline">Edit</span>
                            </Link>
                            <button
                                onClick={() => {
                                    setIsOpen(true)
                                    idRef.current = article.id.toString()
                                }}
                                className="flex items-center gap-1 rounded-md px-2 py-1 text-[#ff0000] hover:bg-red-500/10 transition"
                            >
                                <RiDeleteBinLine className="text-lg" />
                                <span className="hidden md:inline">Delete</span>
                            </button>
                        </div>
                    </div>
                ))}

                {/* Empty State */}
                {articles.length === 0 && (
                    <div className="px-6 py-10 text-center text-sm italic text-gray-500">
                        No articles found.
                    </div>
                )}
            </div>
            <ConfirmDeleteModal
                isOpen={isOpen}
                isLoading={isLoading}
                onClose={() => setIsOpen(false)}
                onConfirm={() => {
                    handleDelete(idRef.current)
                    setIsOpen(false)
                    idRef.current = ""
                }}
            />
        </div>
    )
}
