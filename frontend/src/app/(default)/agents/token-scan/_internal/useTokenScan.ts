import { showAlert } from "@/app/_internal/utils/toast"
import http from "@/client/integration/http"
import { TokenReport } from "@/core/agent"
import { useAppDispatch, useAppSelector } from "@/core/hooks/useRedux"
import { updateCoin } from "@/store/reducer/agentsSlice"
import { useRef, useState } from "react"
import useSWRMutation from "swr/mutation"
import { API_ENDPOINT } from "../../_internal/endpoints"

export const useTokenScan = () => {
    const [selectedCoins, setSelectedCoins] = useState("1")
    const [selectedPeriod, setSelectedPeriod] = useState("1d")
    const coins = useAppSelector((state) => state.agents.coins)
    const keyCoinRef = useRef<string>("BTC")
    const dispatch = useAppDispatch()

    const { trigger, isMutating } = useSWRMutation(
        API_ENDPOINT.TOKEN_SCAN,
        () =>
            http
                .post(API_ENDPOINT.TOKEN_SCAN, {
                    body: JSON.stringify({
                        symbol: keyCoinRef.current,
                        period: selectedPeriod,
                    }),
                })
                .json(),
    )

    const handleGenerateHTML = async () => {
        try {
            if (!keyCoinRef.current) {
                return
            }
            const response = (await trigger()) as TokenReport
            dispatch(
                updateCoin({
                    id: selectedCoins,
                    html: response.result.report.html,
                    pdf: response.result.report.pdf,
                }),
            )
        } catch (error) {
            console.error(error)
        }
    }

    const handleExport = () => {
        let base64Data = coins.find((coin) => coin.id === selectedCoins)?.pdf
        try {
            if (base64Data?.includes("base64,")) {
                base64Data = base64Data.split("base64,")[1]
            }
            const binaryString = atob(base64Data ?? "")
            const bytes = new Uint8Array(binaryString.length)
            for (let i = 0; i < binaryString.length; i++) {
                bytes[i] = binaryString.charCodeAt(i)
            }
            const blob = new Blob([bytes], { type: "application/pdf" })
            const url = URL.createObjectURL(blob)
            const link = document.createElement("a")
            const timestamp = new Date().toISOString().replace(/:/g, "-")
            link.href = url
            link.download = `Ainosha_Agent_Token_Report_${timestamp}.pdf`
            document.body.appendChild(link)
            link.click()
            document.body.removeChild(link)
            setTimeout(() => {
                URL.revokeObjectURL(url)
            }, 100)
            showAlert({
                icon: "success",
                text: "PDF exported successfully.",
                title: "Success",
            })
        } catch (error: any) {
            console.error("Export PDF error", error)
            showAlert({
                icon: "error",
                text: "Export PDF failed.",
                title: "Oops...",
            })
        }
    }
    return {
        coins,
        isLoading: isMutating,
        selectedCoins,
        selectedPeriod,
        keyCoinRef,
        setSelectedCoins,
        handleGenerateHTML,
        setSelectedPeriod,
        handleExport,
    }
}
