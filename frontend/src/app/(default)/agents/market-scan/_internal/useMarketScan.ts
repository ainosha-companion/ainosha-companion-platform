import { showAlert } from "@/app/_internal/utils/toast"
import http from "@/client/integration/http"
import { MarketSentiment } from "@/core/agent"
import { useRef, useState } from "react"
import useSWRMutation from "swr/mutation"
import { API_ENDPOINT } from "../../_internal/endpoints"

export const useMarketScan = () => {
    const [html, setHtml] = useState("")
    const base64Pdf = useRef<string | null>(null)
    const { trigger, isMutating } = useSWRMutation(
        API_ENDPOINT.MARKET_SCAN,
        () => http.get(API_ENDPOINT.MARKET_SCAN).json(),
    )

    const handleGenerateHTML = async () => {
        try {
            const response = (await trigger()) as MarketSentiment
            setHtml(response.result.market.html)
            base64Pdf.current = response.result.market.pdf
        } catch (error) {
            console.error(error)
        }
    }

    const handleExport = async () => {
        let base64Data = base64Pdf.current || ""
        try {
            if (base64Data.includes("base64,")) {
                base64Data = base64Data.split("base64,")[1]
            }
            const binaryString = atob(base64Data)
            const bytes = new Uint8Array(binaryString.length)
            for (let i = 0; i < binaryString.length; i++) {
                bytes[i] = binaryString.charCodeAt(i)
            }
            const blob = new Blob([bytes], { type: "application/pdf" })
            const url = URL.createObjectURL(blob)
            const link = document.createElement("a")
            const timestamp = new Date().toISOString().replace(/:/g, "-")
            link.href = url
            link.download = `Ainosha_Agent_MarketScan_Report_${timestamp}.pdf`
            document.body.appendChild(link)
            link.click()
            document.body.removeChild(link)
            setTimeout(() => {
                URL.revokeObjectURL(url)
            }, 100)
            showAlert({
                icon: "success",
                text: "Export PDF successfully.",
                title: "Success",
            })
        } catch (error: any) {
            console.log("Export PDF error", error)
            showAlert({
                icon: "error",
                text: "Generate PDF failed.",
                title: "Oops...",
            })
        }
    }

    return {
        isLoading: isMutating,
        html,
        handleExport,
        handleGenerateHTML,
    }
}
