"use client"
import Loading from "@/app/loading"
import { useAppDispatch, useAppSelector } from "@/core/hooks/useRedux"
import { toggleLayout, toggleMenu } from "@/store/reducer/themeConfigSlice"
import { PropsWithChildren, useEffect, useState } from "react"

function App({ children }: PropsWithChildren) {
    const themeConfig = useAppSelector((state) => state.themeConfig)
    const dispatch = useAppDispatch()
    const [isLoading, setIsLoading] = useState(true)

    useEffect(() => {
        dispatch(toggleMenu(localStorage.getItem("menu") || themeConfig.menu))
        dispatch(
            toggleLayout(localStorage.getItem("layout") || themeConfig.layout),
        )

        setIsLoading(false)
    }, [dispatch, themeConfig.menu, themeConfig.layout])

    return (
        <div
            className={`${themeConfig.menu} ${themeConfig.layout}  relative font-nunito text-sm font-normal  `}
        >
            {isLoading ? <Loading /> : children}
        </div>
    )
}

export default App
