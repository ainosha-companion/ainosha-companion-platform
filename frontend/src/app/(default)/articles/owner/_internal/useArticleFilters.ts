import { useAppSelector } from "@/core/hooks/useRedux"
import { useMemo } from "react"

const useArticleFilters = () => {
    const categoryName = useAppSelector((state) => state.articles.categoryName)
    const tagName = useAppSelector((state) => state.articles.tagName)
    const query = useAppSelector((state) => state.articles.query)

    const queryString = useMemo(() => {
        const params = new URLSearchParams()

        if (categoryName.length > 0) {
            params.append("filter[category_names]", categoryName.join(","))
        }
        if (tagName.length > 0) {
            params.append("filter[tag_names]", tagName.join(","))
        }
        if (query) {
            params.append("query", query)
        }

        return params.toString()
    }, [categoryName, tagName, query])

    return {
        queryString,
        categoryName,
        tagName,
    }
}

export default useArticleFilters
