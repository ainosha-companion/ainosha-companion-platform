"use client"

import http from "@/client/integration/http"
import { useDebounce } from "@/core/hooks/useDebounce"
import { useAppDispatch } from "@/core/hooks/useRedux"
import { GetPublishedArticlesResponse } from "@/server/article/_internal/type"
import {
    updateAuthorName,
    updateCategoryName,
    updateQuery,
    updateState,
} from "@/store/reducer/articlesSlice"
import { useEffect, useState } from "react"
import useSWR from "swr"
import { useSystem } from "../../../../core/hooks/useSystem"
import { API_ENDPOINT } from "./endpoints"
import useArticleFilters from "./useArticleFilters"

const useArticles = () => {
    const { queryString } = useArticleFilters()
    const debounceValue = useDebounce(queryString, 500)
    const { innerWidth } = useSystem()
    const [windowWidth, setWindowWidth] = useState<number>(innerWidth)

    const [selectedAuthors, setSelectedAuthors] = useState<string[]>([])
    const [viewMode, setViewMode] = useState<"grid" | "table">("grid")
    const [isFilterVisible, setIsFilterVisible] = useState(false)
    const [searchQuery, setSearchQuery] = useState("")
    const dispatch = useAppDispatch()
    useEffect(() => {
        if (typeof window === "undefined") return

        const handleResize = () => {
            setWindowWidth(innerWidth)
        }
        dispatch(updateState({ tagName: [] }))
        dispatch(updateCategoryName("All"))
        dispatch(updateAuthorName("All"))
        dispatch(updateQuery(""))

        window.addEventListener("resize", handleResize)
        return () => window.removeEventListener("resize", handleResize)
    }, [])

    const handleOverlayClick = () => {
        if (windowWidth < 768) {
            setIsFilterVisible(false)
        }
    }

    const toggleFilter = () => {
        setIsFilterVisible(!isFilterVisible)
    }

    const handleCheckboxChange = (authorName: string) => {
        setSelectedAuthors((prev) =>
            prev.includes(authorName)
                ? prev.filter((name) => name !== authorName)
                : [...prev, authorName],
        )
    }

    const apiUrl = debounceValue
        ? `${API_ENDPOINT.GetArticles}?${debounceValue}`
        : API_ENDPOINT.GetArticles

    const { data, error, isLoading } = useSWR<GetPublishedArticlesResponse>(
        apiUrl,
        async (url: string): Promise<GetPublishedArticlesResponse> => {
            const response = await http.get(url)
            return response.json()
        },
    )

    return {
        articles: data?.result?.articles || [],
        isLoading,
        error,
        apiUrl,
        windowWidth,
        selectedAuthors,
        viewMode,
        isFilterVisible,
        searchQuery,
        setSelectedAuthors,
        setViewMode,
        setIsFilterVisible,
        toggleFilter,
        handleOverlayClick,
        setSearchQuery,
        handleCheckboxChange,
    }
}

export default useArticles
