import React, { useEffect, useState, useMemo } from 'react';
import './Waveform.css';

const MIN_BAR_HEIGHT = 4;
const MAX_BAR_HEIGHT = 32;
const TICK_MS = 120;

function generateRandomHeights(count) {
  const heights = new Array(count);
  for (let i = 0; i < count; i += 1) {
    heights[i] = MIN_BAR_HEIGHT + Math.random() * (MAX_BAR_HEIGHT - MIN_BAR_HEIGHT);
  }
  return heights;
}

/**
 * Waveform bars for the IVR test softphone panel.
 *
 * IMPORTANT: The animation must be strictly derived from `callStatus` so it
 * can never diverge from the status label rendered alongside it (e.g.
 * "IDLE \u00b7 ready to dial"). This closes the state-binding gap where the
 * waveform previously animated from component mount regardless of call
 * state (SCRUM-94 / duplicate of SCRUM-14, 15, 22, 84, 92).
 *
 * @param {number} bars - number of waveform bars to render
 * @param {'idle'|'ringing'|'connected'|'ended'|string} callStatus - current call state
 */
export default function Waveform({ bars = 24, callStatus }) {
  const isCallActive = callStatus === 'connected' || callStatus === 'ringing';
  const [heights, setHeights] = useState(() => new Array(bars).fill(MIN_BAR_HEIGHT));

  useEffect(() => {
    if (!isCallActive) {
      // Force a flat, non-animating baseline whenever we are not actively
      // on a call (idle, ended, disconnected, unknown, etc).
      setHeights(new Array(bars).fill(MIN_BAR_HEIGHT));
      return undefined;
    }

    const intervalId = setInterval(() => {
      setHeights(generateRandomHeights(bars));
    }, TICK_MS);

    return () => clearInterval(intervalId);
  }, [isCallActive, bars]);

  const containerClassName = useMemo(
    () => `waveform ${isCallActive ? 'waveform--animating' : 'waveform--idle'}`,
    [isCallActive]
  );

  return (
    <div className={containerClassName} data-call-status={callStatus} aria-hidden="true">
      {heights.map((height, index) => (
        <span key={index} style={{ height: `${height}px` }} />
      ))}
    </div>
  );
}
