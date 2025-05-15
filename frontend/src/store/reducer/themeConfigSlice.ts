import { createSlice } from "@reduxjs/toolkit"
import themeConfig from "theme.config"

const initialState = {
    isDarkMode: true,
    menu: themeConfig.menu,
    layout: themeConfig.layout,
}

const themeConfigSlice = createSlice({
    name: "auth",
    initialState: initialState,
    reducers: {
        toggleMenu(state, { payload }) {
            payload = payload || state.menu // vertical, collapsible-vertical, horizontal
            localStorage.setItem("menu", payload)
            state.menu = payload
        },
        toggleLayout(state, { payload }) {
            payload = payload || state.layout // full, boxed-layout
            localStorage.setItem("layout", payload)
            state.layout = payload
        },
    },
})

export const { toggleMenu, toggleLayout } = themeConfigSlice.actions

export default themeConfigSlice.reducer
