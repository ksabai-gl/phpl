import React, { useEffect, useRef, useState } from 'react';

/**
 * WaveformVisualizer
 * Renders the animated bar visualization for the softphone console.
 * The animation loop must only run while the call is ACTIVE; it must
 * not animate during IDLE / DIALING and must stop + reset on ENDED.
 *
 * Fix for SCRUM-94 / duplicate tickets SCRUM-92, SCRUM-84, SCRUM-15,
 * SCRUM-22, SCRUM-14: waveform previously animated regardless of call
 * state (unguarded setInterval loop running for the component's full
 * mounted lifetime).
 */
function WaveformVisualizer({ callState }) {
  const BAR_COUNT = 20;
  const [levels, setLevels] = useState(new Array(BAR_COUNT).fill(0));
  const intervalRef = useRef(null);

  useEffect(() => {
    const isActive = callState === 'ACTIVE';

    if (isActive && !intervalRef.current) {
      intervalRef.current = setInterval(() => {
        setLevels((prev) => prev.map(() => Math.random()));
      }, 100);
    }

    if (!isActive && intervalRef.current) {
      clearInterval(intervalRef.current);
      intervalRef.current = null;
      setLevels(new Array(BAR_COUNT).fill(0));
    }

    return () => {
      if (intervalRef.current) {
        clearInterval(intervalRef.current);
        intervalRef.current = null;
      }
    };
  }, [callState]);

  const isIdleVisual = callState !== 'ACTIVE';

  return (
    <div className={`waveform ${isIdleVisual ? 'waveform--idle' : ''}`}>
      {levels.map((level, i) => (
        <div
          key={i}
          className="waveform-bar"
          style={{ height: isIdleVisual ? '2px' : `${level * 100}%` }}
        />
      ))}
    </div>
  );
}

export default WaveformVisualizer;
