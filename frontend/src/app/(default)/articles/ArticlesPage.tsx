"use client"

import useArticles from "@/app/(default)/articles/_internal/useArticlesPage"
import { cn } from "@/app/_internal/utils/style"
import LoadingComponent from "@/core/components/loading-component"
import Link from "next/link"
import { FaTable, FaThLarge } from "react-icons/fa"
import {
    ArticleGrid,
    ArticleTable,
    FilterSidebar,
    HeaderMobile,
} from "./_components"

export default function ArticlesPage() {
    const {
        articles,
        isFilterVisible,
        windowWidth,
        viewMode,
        isLoading,
        toggleFilter,
        handleOverlayClick,
        setViewMode,
    } = useArticles()

    return (
        <div className=" bg-gradient-to-b from-gray-900 to-black text-white  rounded-md border-[0.5px] border-primary/30">
            <HeaderMobile toggleFilter={toggleFilter} />

            <div className="grid grid-cols-1 md:grid-cols-12">
                <FilterSidebar
                    isVisible={isFilterVisible}
                    toggleFilter={toggleFilter}
                    handleOverlayClick={handleOverlayClick}
                    windowWidth={windowWidth}
                />

                <main className="col-span-1  p-4 md:col-span-8 md:p-6 lg:col-span-9">
                    <div className="mb-6 flex items-center justify-between">
                        <div className="ml-auto flex items-center gap-3">
                            <button
                                aria-label="Grid view"
                                title="Grid view"
                                className={cn(
                                    "rounded-xl p-2 shadow-sm transition-all duration-300",
                                    viewMode === "grid"
                                        ? "app-gradient text-white shadow-lg"
                                        : "bg-gray-800 text-gray-300 hover:bg-gray-700",
                                )}
                                onClick={() => setViewMode("grid")}
                            >
                                <FaThLarge
                                    size={16}
                                    color={
                                        viewMode === "grid"
                                            ? "#374151"
                                            : undefined
                                    }
                                />
                            </button>
                            <button
                                aria-label="Table view"
                                title="Table view"
                                className={cn(
                                    "rounded-xl p-2 shadow-sm transition-all duration-300",
                                    viewMode === "table"
                                        ? "app-gradient text-white shadow-lg"
                                        : "bg-gray-800 text-gray-300 hover:bg-gray-700",
                                )}
                                onClick={() => setViewMode("table")}
                            >
                                <FaTable
                                    size={16}
                                    color={
                                        viewMode === "table"
                                            ? "#374151"
                                            : undefined
                                    }
                                />
                            </button>
                            <Link
                                href="/articles/owner"
                                className="bg-gradient-to-r from-primary to-gray-300 py-2 sm:px-5 px-3 text-black rounded-md font-bold flex items-center gap-2 hover:scale-105 hover:shadow-lg transition-all duration-300"
                            >
                                <span className="sm:block hidden">
                                    My articles
                                </span>
                            </Link>
                        </div>
                    </div>

                    {articles.length === 0 && !isLoading ? (
                        <div className="flex h-64 flex-col items-center justify-center rounded-lg bg-gray-800 p-6 text-center">
                            <p className="mb-2 text-xl font-semibold text-white">
                                No articles found
                            </p>
                            <p className="text-sm text-gray-400">
                                Try adjusting your filters or search query
                            </p>
                        </div>
                    ) : isLoading ? (
                        <LoadingComponent className="h-64" />
                    ) : viewMode === "grid" ? (
                        <ArticleGrid articles={articles} />
                    ) : (
                        <ArticleTable articles={articles} />
                    )}
                </main>
            </div>
        </div>
    )
}
