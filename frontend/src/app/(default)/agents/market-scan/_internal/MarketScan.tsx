"use client"

import LoadingAnimation from "@/core/components/common/loadingAnimation"
import Image from "next/image"
import { useMarketScan } from "./useMarketScan"

export default function MarketScan() {
    const { html, isLoading, handleGenerateHTML, handleExport } =
        useMarketScan()

    return (
        <div className="flex flex-col md:grid gap-5 h-[calc(100vh-6rem)] md:h-[calc(100vh-8rem)]">
            <div
                className={`panel h-full ${
                    html === "" && "border-[0.5px]"
                } border-primary/30 p-0 flex flex-col items-center justify-center relative`}
            >
                {html !== "" ? (
                    <>
                        <iframe
                            srcDoc={html}
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
                        <div className="transition-transform duration-300 hover:scale-105">
                            <Image
                                src="/assets/images/market-scanner-page.png"
                                alt="Empty Report"
                                width={400}
                                height={400}
                                className=" object-cover rounded-xl"
                            />
                        </div>
                        {/* Tiêu đề */}

                        <h2 className="text-2xl md:text-3xl font-extrabold text-gray-300 text-center">
                            Ready for the Market Scanner?
                        </h2>

                        {/* Mô tả */}
                        <p className="text-center text-gray-400 max-w-sm">
                            No analysis available yet. Tap below to initiate a
                            comprehensive market scan.
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
        </div>
    )
}
