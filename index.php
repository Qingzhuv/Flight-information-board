<!DOCTYPE html>
<html lang="zh-CN">
<!-- 
/**
 * Project: WESTCRAN Flight Status Board
 * Author: WESTCRAN 西鹤软件 (https://westcran.tech)
 * License: GNU GPL v3.0
 * * [法律声明]
 * 1. 任何基于本项目开发的衍生版本必须保留此署名。
 * 2. 禁止将本项目用于任何形式的非法用途，包括但不限于航空诈骗、虚假信息发布。
 * 3. 开发者不对使用者因违反法律导致的任何后果承担责任。
 */
-->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WESTCRAN航班看板</title>
    <script src="assets/jquery.min.js"></script>
    <style>
        :root {
            --csair-blue: #003399;
            --csair-red: #E60012;
            --text-gray: #666666;
            --border-light: #f0f0f0;
        }

        html, body { 
            height: 100%;
            margin: 0; 
            padding: 0;
            font-family: "PingFang SC", "Microsoft YaHei", sans-serif; 
            background: #ffffff; 
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        .top-line { height: 6px; background: var(--csair-red); width: 100%; }

        header { 
            padding: 2vh 4vw;
            border-bottom: 1px solid var(--border-light); 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            background: #fff;
        }

        .logo { font-size: calc(16px + 1vw); font-weight: 700; color: var(--csair-blue); }
        .logo span { font-weight: 300; color: #ccc; margin: 0 10px; }
        .sub-title { font-size: calc(12px + 0.5vw); color: var(--text-gray); font-weight: 400; }
        #clock { font-family: "Helvetica Neue", Arial, sans-serif; font-size: calc(14px + 0.8vw); color: var(--csair-blue); font-weight: 500; }

        .main-content { 
            flex: 1; 
            width: 100%;
            padding: 0;
            box-sizing: border-box;
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            table-layout: fixed;
        }

        th { 
            text-align: left; 
            padding: 2vh 2vw; 
            color: #999; 
            font-size: 14px;
            background: #fafafa;
            border-bottom: 3px solid var(--csair-blue); 
        }

        td { 
            padding: 3vh 2vw; 
            border-bottom: 1px solid #f0f0f0; 
            font-size: calc(14px + 0.4vw);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .flight-no { font-weight: 700; color: var(--csair-blue); font-size: calc(16px + 0.5vw); }
        .dest-col { font-weight: 500; }
        .gate-box { font-weight: bold; font-size: calc(16px + 0.5vw); }

        .status-tag { padding: 0.5vh 1vw; border-radius: 4px; font-size: 14px; background: #f5f5f5; color: #777; border: 1px solid transparent; }
        .status-boarding { background: #e8f5e9; color: #2ecc71; border-color: #2ecc71; font-weight: bold; animation: blink 1.5s infinite; }
        .status-delayed { background: #fff3e0; color: #f39c12; border-color: #f39c12; }
        .status-cancelled { background: #ffebee; color: #e74c3c; border-color: #e74c3c; }

        @keyframes blink { 0% { opacity: 1; } 50% { opacity: 0.6; } 100% { opacity: 1; } }

        footer {
            padding: 3vh 0;
            background: #fff;
            text-align: center;
        }

        .support-text {
            font-size: 12px;
            color: #bbb;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .support-text a { color: #aaa; text-decoration: none; transition: color 0.3s; }
        .support-text a:hover { color: var(--csair-blue); }
    </style>
</head>
<body>

<div class="top-line"></div>

<header>
    <div class="logo">WESTCRAN<span>|</span><span class="sub-title">航班动态 FLIGHT STATUS</span></div>
    <div id="clock">--:--:--</div>
</header>

<div class="main-content">
    <table>
        <thead>
            <tr>
                <th width="15%">日期 DATE</th>
                <th width="15%">航班 FLIGHT</th>
                <th width="30%">目的地 DESTINATION</th>
                <th width="15%">计划时间 TIME</th>
                <th width="12%">登机口 GATE</th>
                <th width="13%">状态 STATUS</th>
            </tr>
        </thead>
        <tbody id="list">
            </tbody>
    </table>
</div>

<footer>
    <div class="support-text">
        <a href="https://westcran.tech" target="_blank">Powered by WESTCRAN | 由西鹤软件提供技术支持</a>
    </div>
</footer>

<script>
    function load() {
        $.ajax({
            url: 'api.php?action=list',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                let h = '';
                if (data.length === 0) {
                    h = '<tr><td colspan="6" style="text-align:center; color:#ccc; padding:10vh;">暂无实时航班数据</td></tr>';
                } else {
                    data.forEach(f => {
                        let stClass = '';
                        if (f.f_status === '登机中') stClass = 'status-boarding';
                        else if (f.f_status === '延误') stClass = 'status-delayed';
                        else if (f.f_status === '取消') stClass = 'status-cancelled';

                        h += `<tr>
                            <td style="color:#999">${f.f_date}</td>
                            <td class="flight-no">${f.f_no}</td>
                            <td class="dest-col">${f.f_dest}</td>
                            <td style="font-family:monospace">${f.f_time ? f.f_time.substring(0, 5) : '--:--'}</td>
                            <td class="gate-box">${f.f_gate || '--'}</td>
                            <td><span class="status-tag ${stClass}">${f.f_status}</span></td>
                        </tr>`;
                    });
                }
                $('#list').html(h);
            }
        });
    }

    function updateClock() {
        const now = new Date();
        $('#clock').text(now.toLocaleTimeString('zh-CN', { hour12: false }));
    }

    setInterval(load, 10000);
    setInterval(updateClock, 1000);
    $(document).ready(() => { load(); updateClock(); });
</script>

</body>
</html>
