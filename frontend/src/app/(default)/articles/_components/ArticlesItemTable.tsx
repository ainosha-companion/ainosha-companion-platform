"use client"

import { formatRelativeTime } from "@/app/_internal/utils/formatRelativeTime"
import Image from "next/image"
import Link from "next/link"
import { ArticleListItem } from "../_internal/type"

type Props = {
    articles: ArticleListItem[]
}

export default function ArticleGrid({ articles }: Props) {
    return (
        <div className="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            {articles.map((article) => (
                <Link
                    key={article.id}
                    href={`/articles/${article.slug}`}
                    className="group flex flex-col overflow-hidden rounded-2xl bg-gradient-to-br from-gray-800 via-gray-900 to-black shadow-md transition-transform duration-300 hover:scale-[1.02] hover:shadow-2xl"
                >
                    <div className="relative h-48 w-full overflow-hidden">
                        <Image
                            src={
                                article?.thumbnail ||
                                "/assets/images/img-articles.jpg"
                            }
                            alt={article.title}
                            fill
                            className="object-cover transition-transform duration-300 group-hover:scale-110"
                        />
                    </div>

                    <div className="flex flex-grow flex-col justify-between p-5 space-y-3">
                        <div className="flex items-center justify-between text-xs text-gray-400">
                            <span
                                className={`rounded-full px-3 py-1 text-[11px] uppercase tracking-wide ${
                                    !article?.category?.name?.toLowerCase()
                                        ? "bg-gray-700 border border-gray-600 text-gray-300"
                                        : "bg-blue-600 border border-blue-500 text-white"
                                }`}
                            >
                                {article?.category?.name || "Uncategorized"}
                            </span>
                            <span className="text-xs text-gray-400">
                                {article.created_at
                                    ? formatRelativeTime(article.created_at)
                                    : "N/A"}
                            </span>
                        </div>

                        <h3 className="text-lg font-semibold text-white group-hover:text-primary leading-snug line-clamp-2">
                            {article.title}
                        </h3>

                        <p className="flex flex-row items-center text-sm text-gray-400 leading-relaxed line-clamp-3 gap-2">
                            <span className="  border-primary bg-gray-700/40 text-primary px-2 rounded-full">
                                {article?.tags?.slice(0, 2).map((tag) => (
                                    <span
                                        key={tag}
                                        className="inline-block mr-1"
                                    >
                                        {tag}
                                    </span>
                                ))}
                            </span>
                        </p>
                        <p className="flex flex-row items-center">
                            <Image
                                src="/assets/images/user-profile.jpeg"
                                alt="User"
                                width={32}
                                height={32}
                                className="rounded-full inline-block mr-2"
                            />
                            <span className="text-sm text-gray-400">
                                John Doe
                            </span>
                        </p>

                        {/* <p className="text-sm text-gray-400 leading-relaxed line-clamp-3">
                            {article?.abstract || "No summary available."}
                        </p> */}
                    </div>
                </Link>
            ))}
        </div>
    )
}
