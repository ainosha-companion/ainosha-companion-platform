import { createSlice } from "@reduxjs/toolkit"

interface ArticlesState {
    articles: any[]
    query: string
    categoryName: string[]
    authorName: string[]
    tagName: string[]
}

const initialState: ArticlesState = {
    articles: [],
    query: "",
    authorName: [],
    categoryName: [],
    tagName: [],
}

const articlesSLice = createSlice({
    name: "articles",
    initialState: initialState,
    reducers: {
        updateState: (state, action) => {
            return { ...state, ...action.payload }
        },
        updateQuery: (state, action) => {
            return { ...state, query: action.payload }
        },
        updateAuthorName: (state, action) => {
            if (action.payload.toLowerCase() === "all") {
                return { ...state, authorName: [] }
            }
            const authorName =
                state.authorName.findIndex((val) => val === action.payload) ===
                -1 // payload is not in array yet
                    ? [...state.authorName, action.payload]
                    : state.authorName.filter((val) => val !== action.payload)
            return {
                ...state,
                authorName,
            }
        },
        updateCategoryName: (state, action) => {
            if (action.payload.toLowerCase() === "all") {
                return { ...state, categoryName: [] }
            }
            const categoryName =
                state.categoryName.findIndex(
                    (val) => val === action.payload,
                ) === -1 // payload is not in array yet
                    ? [...state.categoryName, action.payload]
                    : state.categoryName.filter((val) => val !== action.payload)
            return {
                ...state,
                categoryName,
            }
        },
        updateTagName: (state, action) => {
            if (action.payload.toLowerCase() === "all") {
                return { ...state, tagName: [] }
            }
            const tagName =
                state.tagName.findIndex((val) => val === action.payload) === -1 // payload is not in array yet
                    ? [...state.tagName, action.payload]
                    : state.tagName.filter((val) => val !== action.payload)
            return {
                ...state,
                tagName,
            }
        },
    },
})

export const {
    updateAuthorName,
    updateCategoryName,
    updateTagName,
    updateQuery,
    updateState,
} = articlesSLice.actions

export default articlesSLice.reducer
