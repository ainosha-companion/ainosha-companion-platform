"use client"
import LoadingComponent from "@/core/components/loading-component"
import { ArticleTableAdmin, FilterSidebar, HeaderMobile } from "../_components"
import CreateArticleModal from "./_internal/CreateArticleModal"
import useArticles from "./_internal/useArticlesPage"

const MyArticlePage = () => {
    const {
        isFilterVisible,
        articles,
        isLoading,
        toggleFilter,
        handleOverlayClick,
        windowWidth,
    } = useArticles()
    return (
        <div className="min-h-screen bg-gradient-to-b from-gray-900 to-black text-white  rounded-md border-[0.5px] border-primary/30">
            <HeaderMobile toggleFilter={toggleFilter} />
            <div className="grid grid-cols-1 md:grid-cols-12">
                <FilterSidebar
                    isVisible={isFilterVisible}
                    toggleFilter={toggleFilter}
                    handleOverlayClick={handleOverlayClick}
                    windowWidth={windowWidth}
                    isShowAuthors={false}
                />

                <main className="relative col-span-1 min-h-screen p-4 md:col-span-8 lg:col-span-9">
                    <div className="mb-4 flex justify-end min-h-10">
                        <CreateArticleModal classname="hover:scale-[1.05] transition-all duration-300" />
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
                    ) : (
                        <ArticleTableAdmin articles={articles} />
                    )}
                </main>
            </div>
        </div>
    )
}

export default MyArticlePage
