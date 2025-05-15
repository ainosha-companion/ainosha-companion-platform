import articlesSlice from "@/store/reducer/articlesSlice"
import themeConfigSlice from "@/store/reducer/themeConfigSlice"
import { combineReducers, configureStore } from "@reduxjs/toolkit"
import agentsSlice from "./reducer/agentsSlice"

const rootReducer = combineReducers({
    themeConfig: themeConfigSlice,
    articles: articlesSlice,
    agents: agentsSlice,
})

export const store = configureStore({
    reducer: rootReducer,
})

export type RootState = ReturnType<typeof store.getState>
export type AppDispatch = typeof store.dispatch
