import React from 'react'
import PacmanLoader from 'react-spinners/PacmanLoader'

export default function SpinnerComponent() {
    return (
        <div className = 'sweet-loading' >
        <PacmanLoader
            size = { 20 }
            color = { '#3f51b5' }
            loading = { true }
        />
        </div>
    )
}
