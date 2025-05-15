import Info from "./Info"

const agents = [
    {
        id: "1",
        name: "Market Scan",
        type: "market-sentiment",
        slug: "market-scan",
        description:
            "A digital assistant with advanced capabilities for analyzing entire cryptocurrency markets. This AI evaluates multiple coins simultaneously, tracks market trends, and identifies patterns across different assets to provide comprehensive insights into market movements and investment opportunities.",
        avatarURL: "/assets/images/bg-market-scan.jpg",
    },
    {
        id: "2",
        name: "Token Scan",
        type: "coin",
        slug: "token-scan",
        description:
            "A specialized assistant focused on deep analysis of individual cryptocurrencies. This AI performs detailed examination of specific coins, evaluating fundamentals, technical metrics, and historical performance to generate precise insights and forecasts for single crypto assets.",
        avatarURL: "/assets/images/bg-token-scan.jpg",
    },
]
const AgentsPage = () => {
    return (
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 min-h-[calc(100vh_-_6rem)] md:min-h-[calc(100vh_-_8rem)] ">
            {agents.map((agent) => (
                <div
                    key={agent.id}
                    className="panel hover:border-primary hover:scale-[1.01] transition-all duration-300 h-full border-[0.5px] border-primary/30"
                >
                    <Info profile={agent} />
                </div>
            ))}
        </div>
    )
}

export default AgentsPage
