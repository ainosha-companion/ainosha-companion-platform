"use client"

import IconDocument from "@/core/components/icon/icon-document"
import { useEffect, useState } from "react"

export default function ArticleProgressHeader({
    title,
    category,
    author,
    tags,
}: {
    title: string
    category: string
    author: string
    tags?: string[]
}) {
    const [isVisible, setIsVisible] = useState(false)
    const [readingProgress, setReadingProgress] = useState(0)

    useEffect(() => {
        const article = document.getElementById("article") || document.body
        const mainHeader = document.querySelector("header")
        const articleInfo = document.getElementById("article-info")

        let mainHeaderHeight = 0
        if (mainHeader || articleInfo) {
            mainHeaderHeight =
                mainHeader!.offsetHeight + articleInfo!.offsetHeight
        }

        const handleScroll = () => {
            const articleHeight = article.clientHeight
            const windowHeight = window.innerHeight
            const scrollPosition = window.scrollY
            const totalScrollableDistance = articleHeight - windowHeight

            // Check visibility of header
            if (scrollPosition > mainHeaderHeight) {
                setIsVisible(true)
            } else {
                setIsVisible(false)
            }

            // Calculate reading progress
            if (totalScrollableDistance > 0) {
                const progress =
                    (scrollPosition / totalScrollableDistance) * 100
                setReadingProgress(Math.min(Math.max(progress, 0), 100))
            }
        }

        // Using requestAnimationFrame for smoother animation
        const optimizedHandleScroll = () => requestAnimationFrame(handleScroll)

        window.addEventListener("scroll", optimizedHandleScroll)
        return () => window.removeEventListener("scroll", optimizedHandleScroll)
    }, [])

    return (
        <div
            className={`fixed top-0 left-0 w-full bg-gradient-to-r from-gray-900 to-black shadow-lg z-50 transition-transform duration-300 ease-in-out ${
                isVisible
                    ? "transform translate-y-0 opacity-100"
                    : "transform -translate-y-full opacity-0 pointer-events-none"
            }`}
        >
            <div className="container mx-auto px-4 py-4 flex items-center">
                <div className="flex flex-col space-y-1 max-w-2xl">
                    <span className="text-xs font-medium text-blue-400 uppercase tracking-wider">
                        {category}
                    </span>
                    <div className="flex items-center gap-4">
                        <IconDocument className="flex-shrink-0 w-8 h-8 text-gray-300" />
                        <div>
                            <h2 className="text-lg font-bold truncate pr-4 text-white">
                                {title}
                            </h2>
                            <span className="text-gray-300 flex items-center gap-1">
                                <span className="text-gray-400">By</span>{" "}
                                <span className="font-medium text-white">
                                    {author}
                                </span>
                            </span>
                        </div>
                    </div>
                    <div className="flex items-center text-sm gap-4">
                        <div className="flex gap-2">
                            {tags?.map((tag, index) => (
                                <span
                                    key={index}
                                    className="px-2.5 py-0.5 bg-gray-800 border border-gray-700 rounded-full text-xs font-medium text-blue-400 flex items-center"
                                >
                                    <span className="w-1.5 h-1.5 rounded-full bg-blue-400 mr-1"></span>
                                    {tag}
                                </span>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
            <div className="h-[3px] bg-gray-800 w-full">
                <div
                    className="h-full bg-primary transition-width duration-300 ease-out"
                    style={{ width: `${readingProgress}%` }}
                />
            </div>
        </div>
    )
}
