"use client"

import { formatRelativeTime } from "@/app/_internal/utils/formatRelativeTime"
import { Article } from "@/server/article/_internal/type"
import Image from "next/image"
import Link from "next/link"
import {
    FaFolderOpen,
    FaNewspaper,
    FaRegClock,
    FaUserPen,
} from "react-icons/fa6"

type Props = {
    articles: Article[]
}

export default function ArticleTable({ articles }: Props) {
    return (
        <div className="overflow-x-auto rounded-2xl border border-gray-800 bg-gradient-to-br from-gray-900 to-gray-950 shadow-lg">
            <div className="min-w-full">
                {/* Header */}
                <div className="grid grid-cols-12 items-center border-b border-gray-800 bg-gray-900/80 px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-300 backdrop-blur-sm">
                    <div className="col-span-5 flex items-center gap-2">
                        <FaNewspaper className="text-primary" />
                        <span>Article</span>
                    </div>
                    <div className="col-span-2 flex items-center gap-2">
                        <FaRegClock className="text-primary" />
                        <span>Date</span>
                    </div>
                    <div className="col-span-2 flex items-center gap-2">
                        <FaFolderOpen className="text-primary" />
                        <span>Category</span>
                    </div>
                    <div className="col-span-3 flex items-center gap-2">
                        <FaUserPen className="text-primary" />
                        <span>Author</span>
                    </div>
                </div>

                {/* Rows */}
                {articles.map((article) => (
                    <Link
                        key={article.id}
                        href={`/articles/${article.slug}`}
                        className="block hover:bg-gray-800/60 transition duration-150"
                    >
                        <div className="grid grid-cols-12 items-center border-b border-gray-800 px-4 py-4 text-sm text-gray-300">
                            {/* Article Title */}
                            <div className="col-span-5 flex items-center gap-4">
                                <div className="relative h-14 w-14 flex-shrink-0 overflow-hidden rounded-lg bg-gray-700 shadow">
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
                                <span className="line-clamp-2 font-medium text-white mr-4">
                                    {article.title}
                                </span>
                            </div>

                            {/* Date */}
                            <div className="col-span-2 text-xs text-gray-400">
                                {article.updated_at
                                    ? formatRelativeTime(article.updated_at)
                                    : "N/A"}
                            </div>

                            {/* Category */}
                            <div className="col-span-2 text-xs text-gray-400 italic">
                                {article?.category?.name || "Uncategorized"}
                            </div>

                            {/* Author */}
                            <div className="col-span-3 text-xs text-gray-400 italic">
                                {article.author?.name || "Unknown"}
                            </div>
                        </div>
                    </Link>
                ))}

                {/* Empty State */}
                {articles.length === 0 && (
                    <div className="py-10 text-center text-sm italic text-gray-500">
                        No articles found.
                    </div>
                )}
            </div>
        </div>
    )
}
