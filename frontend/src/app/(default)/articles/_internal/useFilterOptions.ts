"use client"

import http from "@/client/integration/http"
import useSWR from "swr"
import * as v from "valibot"

import { GetAuthorsSchema } from "@/server/article/author/_internal/schema"
import { GetCategoriesSchema } from "@/server/article/category/_internal/schema"

import { useDebounce } from "@/core/hooks/useDebounce"
import { useAppDispatch, useAppSelector } from "@/core/hooks/useRedux"
import type { Author } from "@/server/article/author/_internal/type"
import type { Category } from "@/server/article/category/_internal/type"
import type { Tag } from "@/server/article/tag/_internal/type"
import { updateQuery } from "@/store/reducer/articlesSlice"
import { useEffect, useState } from "react"
import { API_ENDPOINT } from "./endpoints"

const fetchCategories = async (): Promise<Category[]> => {
    try {
        const res = await http.get(API_ENDPOINT.GetCategories).json()
        const parsed = v.parse(GetCategoriesSchema, res)
        return parsed.result.categories
    } catch (error) {
        console.log("fetch categories error:", error)
        return []
    }
}

const fetchTags = async (): Promise<Tag[]> => {
    const res = (await http.get(API_ENDPOINT.GetTags).json()) as Tag[]
    return res
}

const fetchAuthors = async (): Promise<Author[]> => {
    const res = await http.get(API_ENDPOINT.GetAuthors).json()
    const parsed = v.parse(GetAuthorsSchema, res)
    return parsed.authors
}

export default function useFilterOptions() {
    const dispatch = useAppDispatch()
    const authorName = useAppSelector((state) => state.articles.authorName)
    const categoryName = useAppSelector((state) => state.articles.categoryName)
    const tagName = useAppSelector((state) => state.articles.tagName)
    const [searchQuery, setSearchQuery] = useState("")
    const debouncedValue = useDebounce(searchQuery, 1000)
    const { data: categories = [], isLoading: isCategoriesLoading } = useSWR(
        API_ENDPOINT.GetCategories,
        fetchCategories,
    )
    const { data: tags = [], isLoading: isTagsLoading } = useSWR(
        API_ENDPOINT.GetTags,
        fetchTags,
    )
    const { data: authors = [], isLoading: isAuthorsLoading } = useSWR(
        API_ENDPOINT.GetAuthors,
        fetchAuthors,
    )
    console.log("debouncedValue", debouncedValue)

    useEffect(() => {
        if (typeof window === "undefined") return
        dispatch(updateQuery(debouncedValue))
    }, [debouncedValue])

    return {
        categories: categories,
        tags: tags,
        authors: authors,
        isCategoriesLoading,
        isTagsLoading,
        isAuthorsLoading,
        authorName,
        categoryName,
        tagName,
        searchQuery,
        setSearchQuery,
        debouncedValue,
    }
}
