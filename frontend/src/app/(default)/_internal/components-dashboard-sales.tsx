"use client"
import dynamic from "next/dynamic"

const RealtimeChartDynamic = dynamic(
    () => import("./component-realtime-chart"),
    {
        ssr: false,
        loading: () => <p>Loading...</p>,
    },
)
const TopStoryDynamic = dynamic(() => import("./component-top-story"), {
    ssr: false,
    loading: () => <p>Loading...</p>,
})
const CoinHeadMapDynamic = dynamic(() => import("./component-coin-head-map"), {
    ssr: false,
    loading: () => <p>Loading...</p>,
})
const MiniChartDynamic = dynamic(() => import("./component-mini-chart"), {
    ssr: false,
    loading: () => <p>Loading...</p>,
})

const ComponentsDashboardSales = () => {
    return (
        <div>
            <div className="grid gap-6  xl:grid-cols-3">
                <div className="grid xl:col-span-2">
                    <div className="mb-6 grid gap-6 xl:grid-cols-3">
                        <div className="panel min-h-[460px] xl:col-span-3">
                            <RealtimeChartDynamic />
                        </div>
                    </div>
                    <div className="6 grid gap-6 xl:grid-cols-4">
                        <div className="panel flex flex-col xl:col-span-2">
                            <MiniChartDynamic />
                        </div>
                        <div
                            className="panel flex h-auto flex-col xl:col-span-2"
                            style={{ aspectRatio: 1 }}
                        >
                            <div className="flex h-full flex-1 rounded-lg bg-white dark:bg-black">
                                <CoinHeadMapDynamic />
                            </div>
                        </div>
                    </div>
                </div>
                <div className="panel flex h-auto xl:col-span-1">
                    <TopStoryDynamic />
                </div>
            </div>
        </div>
    )
}

export default ComponentsDashboardSales
