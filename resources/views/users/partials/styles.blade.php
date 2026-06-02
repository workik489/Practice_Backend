<style>
    * { box-sizing: border-box; }
    body { margin: 0; min-height: 100vh; background: #f6f7f9; color: #18181b; font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
    main { width: 100%; max-width: 760px; margin: 0 auto; padding: 32px 24px; }
    header { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 28px; }
    h1 { margin: 0; font-size: 28px; line-height: 1.2; letter-spacing: 0; }
    p { margin: 6px 0 0; color: #62646a; font-size: 14px; }
    .panel { border: 1px solid #dfe3e8; border-radius: 8px; background: #fff; padding: 24px; }
    .grid { display: grid; gap: 18px; }
    label { display: grid; gap: 7px; color: #3f3f46; font-size: 14px; font-weight: 700; }
    input, textarea { width: 100%; border: 1px solid #c9ccd3; border-radius: 6px; background: #fff; color: #18181b; padding: 11px 12px; font: inherit; }
    input:focus, textarea:focus { outline: 2px solid #a7f3d0; border-color: #116149; }
    textarea { min-height: 92px; resize: vertical; }
    .error { color: #b91c1c; font-size: 13px; font-weight: 600; }
    .hint { color: #71717a; font-size: 13px; font-weight: 500; }
    .actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px; }
    button, .button { display: inline-flex; align-items: center; justify-content: center; min-height: 38px; border-radius: 6px; border: 1px solid #c9ccd3; background: #fff; color: #18181b; padding: 9px 14px; font-size: 14px; font-weight: 700; text-decoration: none; cursor: pointer; }
    button:hover, .button:hover { background: #eef0f3; }
    .primary { border-color: #116149; background: #116149; color: #fff; }
    .primary:hover { background: #0d4f3b; }
    @media (max-width: 640px) {
        main { padding: 24px 16px; }
        header { align-items: flex-start; flex-direction: column; }
        .panel { padding: 18px; }
        .actions, .actions .button, .actions button, header .button { width: 100%; }
    }
</style>
