import { createSlice } from "@reduxjs/toolkit"

interface AgentState {
    coins: {
        id: string
        name: string
        banner: string
        logo: string
        html?: string
        pdf?: string
    }[]
}

const initialState: AgentState = {
    coins: [
        {
            id: "1",
            name: "Bitcoin",
            logo: "/assets/images/bitcoin.png",
            banner: "/assets/images/token-scanner.png",
            html: "",
            pdf: "",
        },
        {
            id: "2",
            name: "Ethereum",
            logo: "/assets/images/ethereum.png",
            banner: "/assets/images/ethereum-scanner.png",
            html: "",
            pdf: "",
        },
        {
            id: "3",
            name: "Chain Link",
            logo: "/assets/images/chainlink.png",
            banner: "/assets/images/chainlink-scanner.png",
            html: "",
            pdf: "",
        },
    ],
}

const agentsSlice = createSlice({
    name: "agents",
    initialState: initialState,
    reducers: {
        updateCoin: (state, action) => {
            return {
                ...state,
                coins: state.coins.map((coin) => {
                    if (coin.id === action.payload.id) {
                        return {
                            ...coin,
                            html: action.payload.html,
                            pdf: action.payload.pdf,
                        }
                    }
                    return coin
                }),
            }
        },
    },
})

export const { updateCoin } = agentsSlice.actions

export default agentsSlice.reducer
