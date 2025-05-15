"use client"

import http from "@/client/integration/http"
import { useDebounce } from "@/core/hooks/useDebounce"
import { useAppDispatch } from "@/core/hooks/useRedux"
import { useSystem } from "@/core/hooks/useSystem"
import {
    updateCategoryName,
    updateQuery,
    updateTagName,
} from "@/store/reducer/articlesSlice"
import { useEffect, useState } from "react"
import useSWR from "swr"
import { API_ENDPOINT } from "../../_internal/endpoints"
import { ArticleListItem } from "../../_internal/type"
import useArticleFilters from "./useArticleFilters"

const useArticles = () => {
    const { queryString } = useArticleFilters()
    const debounceValue = useDebounce(queryString, 500)
    const { innerWidth } = useSystem()
    const [windowWidth, setWindowWidth] = useState<number>(innerWidth)
    const dispatch = useAppDispatch()

    const [selectedAuthors, setSelectedAuthors] = useState<string[]>([])
    const [isFilterVisible, setIsFilterVisible] = useState(false)

    useEffect(() => {
        if (typeof window === "undefined") return

        const handleResize = () => {
            setWindowWidth(innerWidth)
        }
        dispatch(updateTagName("All"))
        dispatch(updateCategoryName("All"))
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
        ? `${API_ENDPOINT.AdminGetArticles}?${debounceValue}`
        : API_ENDPOINT.AdminGetArticles

    const { data, error, isLoading } = useSWR<ArticleListItem[]>(
        apiUrl,
        async (url: string): Promise<ArticleListItem[]> => {
            const response = await http.get(url)
            return response.json()
        },
    )

    return {
        articles: data || [],
        isLoading,
        error,
        apiUrl,
        windowWidth,
        selectedAuthors,
        isFilterVisible,
        setSelectedAuthors,
        setIsFilterVisible,
        toggleFilter,
        handleOverlayClick,
        handleCheckboxChange,
    }
}

export default useArticles
