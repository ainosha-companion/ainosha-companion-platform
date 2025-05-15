"use client"

import LoadingAnimation from "@/core/components/common/loadingAnimation"
import dynamic from "next/dynamic"
import Image from "next/image"
import { useTokenScan } from "./useTokenScan"

const CoinItemDynamic = dynamic(() => import("./TokenItem"), {
    ssr: false,
})

export default function CoinDeep() {
    const {
        isLoading,
        coins,
        selectedCoins,
        selectedPeriod,
        keyCoinRef,
        handleExport,
        handleGenerateHTML,
        setSelectedCoins,
        setSelectedPeriod,
    } = useTokenScan()

    const coin = coins.find((coin) => coin.id === selectedCoins)
    console.log("htmlcoin", coin)

    return (
        <div className="flex flex-col md:grid md:grid-cols-10 gap-5 min-h-[calc(100vh_-_6rem)] md:min-h-[calc(100vh_-_8rem)] overflow-hidden">
            {/* Left Panel */}
            <>
                <div className="panel relative z-10 col-span-2 md:col-span-2 flex h-full flex-col space-y-4 overflow-hidden border-[0.5px] border-primary/30 p-4">
                    <div className="relative w-full">
                        <select
                            value={selectedPeriod}
                            onChange={(e) => setSelectedPeriod(e.target.value)}
                            className="appearance-none w-full rounded-md border border-white-light bg-white px-2 pr-10 py-2 text-sm font-semibold text-black !outline-none focus:ring-transparent dark:border-[#17263c] dark:bg-[#121e32] dark:text-white-dark"
                        >
                            {[
                                "1m",
                                "5m",
                                "15m",
                                "30m",
                                "1h",
                                "4h",
                                "1d",
                                "7d",
                                "30d",
                            ].map((p) => (
                                <option key={p} value={p} disabled={p !== "1d"}>
                                    {p}
                                </option>
                            ))}
                        </select>

                        {/* Chevron Icon */}
                        <div className="pointer-events-none absolute inset-y-0 right-2 flex items-center">
                            <svg
                                className="w-4 h-4 text-gray-400 dark:text-white-dark"
                                fill="none"
                                stroke="currentColor"
                                strokeWidth={2}
                                viewBox="0 0 24 24"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    d="M19 9l-7 7-7-7"
                                />
                            </svg>
                        </div>
                    </div>

                    <ul className="flex flex-col gap-3 overflow-auto scroll-smooth">
                        {coins.map((item) => {
                            return (
                                <CoinItemDynamic
                                    key={item.id}
                                    {...item}
                                    isActive={selectedCoins === item.id}
                                    onSelect={(id) => {
                                        switch (id) {
                                            case "1":
                                                keyCoinRef.current = "BTC"
                                                break
                                            case "2":
                                                keyCoinRef.current = "ETH"
                                                break
                                            case "3":
                                                keyCoinRef.current = "LINK"
                                                break
                                        }
                                        setSelectedCoins(id)
                                    }}
                                />
                            )
                        })}
                    </ul>
                </div>
                <div
                    className={`panel flex-grow md:col-span-8 ${
                        coin?.html === "" && "border-[0.5px]"
                    } border-primary/30 p-0 flex items-center justify-center`}
                >
                    {coin?.html !== "" ? (
                        <>
                            <iframe
                                srcDoc={coin?.html}
                                className="w-full h-full border border-black rounded-lg"
                            />

                            <button
                                onClick={handleExport}
                                className="absolute top-4 right-4 px-4 py-2 rounded-full 
                            bg-primary text-black font-bold tracking-wide
                            transition-all duration-300 hover:bg-primary/90 hover:shadow-md
                            focus:outline-none active:scale-95 z-10"
                            >
                                Export PDF
                            </button>
                        </>
                    ) : (
                        <div className="flex flex-col items-center justify-center gap-4">
                            <div className="transition-transform duration-300 hover:scale-105 w-[400px] h-[400px]">
                                <Image
                                    src={coin?.banner}
                                    alt="Coin Banner"
                                    width={400}
                                    height={400}
                                    className="w-full h-full object-contain rounded-xl"
                                />
                            </div>

                            {/* Tiêu đề */}

                            <h2 className="text-2xl md:text-3xl font-extrabold text-gray-300 text-center">
                                Ready for the {coin?.name} Scanner?
                            </h2>

                            {/* Mô tả */}
                            <p className="text-center text-gray-400 max-w-base">
                                No token data available yet. Tap below to start
                                a full {coin?.name} scan.
                            </p>
                            <button
                                onClick={handleGenerateHTML}
                                disabled={isLoading}
                                className={`
                            px-6 h-10 rounded-full 
                            font-bold tracking-wider 
                            border border-primary 
                            text-primary 
                            transition-all duration-300 
                            ${!isLoading ? "hover:scale-105 hover:shadow-lg hover:bg-primary/80 hover:text-white" : ""}
                            focus:outline-none 
                            active:scale-95
                            disabled:opacity-70 disabled:cursor-not-allowed
                            inline-flex items-center justify-center
                        `}
                            >
                                {isLoading ? (
                                    <>
                                        <LoadingAnimation />
                                        <span>Generating Report...</span>
                                    </>
                                ) : (
                                    "Generate Report"
                                )}
                            </button>
                        </div>
                    )}
                </div>
            </>
        </div>
    )
}
