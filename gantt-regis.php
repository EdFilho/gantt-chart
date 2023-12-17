<div class="tab-pane fade in show active" id="panel18c" role="tabpanel">
        <br>
        <style>
            /* RESET RULES
            –––––––––––––––––––––––––––––––––––––––––––––––––– */
            :root {
            --white: #fff;
            --divider: #c6cdd6;
            --body: #f5f7f8;
            }

            /* * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            } */

            ul {
            list-style: none;
            margin-bottom: 0;
            }

            a {
            text-decoration: none;
            color: inherit;
            }

            .rowx {
            display: flex;
            width: 100%;
            }

            .chart-wrapper {
            max-width: 100vw;
            padding: 0 0;
            margin: 0 auto;
            }

            .step-rowx {
            display: flex;
            }

            .step-col {
            flex: 1;
            padding: 0 10px;
            }
            /* CHART-VALUES
            –––––––––––––––––––––––––––––––––––––––––––––––––– */
            .chart-wrapper .chart-values {
            position: relative;
            display: flex;
            padding-bottom: 20px;
            font-weight: bold;
            font-size: 10pt;
            height: 42px;
            text-align: center;
            background-color: #f1f2f5;
            }

            .step-title {
            display: flex;
            height: 42px;
            /* align-self: center; */
            margin-bottom: 0 !important;
            padding-bottom: 20px;
            flex-wrap: wrap;
            text-align: center;
            padding: 0 61px;
            align-content: center;
            font-size: 10pt;
            background-color: #f1f2f5;
            border-right: solid 2px #c6cdd6;
            width: 164px !important;
            }

            .step-label {
            font-size: 10pt;
            align-self: left;
            padding-bottom: 20px;
            flex-wrap: wrap;
            text-align: left;
            padding: 20px 8px 20px 8px;
            align-content: left;
            width: 164px !important;
            border-right: solid 2px #c6cdd6;
            margin-bottom: 0 !important;
            }
            .chart-wrapper .chart-values li {
            flex: 1;
            align-self: center;
            min-width: 55px;
            text-align: center;
            }

            .chart-wrapper .chart-values li:not(:last-child) {
            position: relative;
            }

            .chart-wrapper .chart-values li:not(:last-child)::before {
            content: '';
            position: absolute;
            right: 0;
            height: 300px;
            border-right: 1px solid #dfe4eb;
            }
            .step-label a{
                text-decoration: none;
            }
            .abreChamado{
                cursor: pointer;
                font-weight: 500;
            }

            /* CHART-BARS
            –––––––––––––––––––––––––––––––––––––––––––––––––– */
            .chart-wrapper .chart-bars li {
            position: relative;
            color: var(--white);
            padding-bottom: 15px;
            font-size: 16px;
            border-radius: 8px;
            min-height: 25px;
            padding: 10px 20px;
            width: 0;
            opacity: 0;
            transition: all 0.65s linear 0.2s;
            text-align: center;
            }

            .chart-bars{
                padding-inline-start: 0;
                width: 100%;
            }
            .chart-values{
                padding-inline-start: 0;
            }

            .zebrinha{
                align-items: center;
            }
            .zebrinha:nth-child(even){
                background-color: #f1f2f5;
            }
            .zebrinha > li {
                max-height: 10px;
                padding: 0 !important;
            }

        </style>
        <div id="gantinho" style="max-width: 100%; overflow-x: auto;">
            <div class="chart-wrapper">
                <div>
                    <div class="rowx">
                    <h1 class="step-title">ETAPA</h1>
                    <ul class="chart-values"></ul>
                    </div>
                </div>
                <div class="step-rowx">
                    <div class="rowx">
                    <ul class="chart-bars"></ul>
                    </div>
                </div>
            </div>
        </div>
        <script>
			var exampleTasks = "";
			var contador = 0;

			function carregaGantt() {

				$.ajax({
					url: 'includes/listCronograma.php?id=<?=$id?>', 
					dataType: 'json',
					success: function(data) {
						
						exampleTasks = data.map(function(task) {
							task.start = new Date(task.start);
							task.start.setDate(task.start.getDate() + 1);
							task.end = new Date(task.end);
							task.end.setDate(task.end.getDate() + 1);
							return task;
						});
						console.log(exampleTasks); 
						createChart();
						$(".abreChamado").on('click', function(){
							var chamado = $(this).attr('data-chamado');
							window.open('chamado.php?id='+chamado);
						});
					},
					error: function(xhr, status, error) {
						console.error(status + ": " + error);
					}
				});
			}

            function createChart(e) {
				const daysOfCurrentMonth = getDaysInCurrentMonth();
				const daysInNextMonth = getDaysInNextMonth();

				populateChartValues(daysOfCurrentMonth);
				populateChartValues(daysInNextMonth);
				populateChartBars(exampleTasks);

				const days = document.querySelectorAll('.chart-values li');
				const tasks = document.querySelectorAll('.chart-bars li');
				const daysArray = [...days];

				tasks.forEach((el) => {
					contador++;
					const duration = el.dataset.duration.split('-');
					const startDay = duration[0];
					const endDay = duration[1];
					let left = 0,
					width = 0;

					if (startDay.endsWith('½')) {
					const filteredArray = daysArray.filter(
						(day) => day.textContent == startDay.slice(0, -1)
					);
					left = filteredArray[0].offsetLeft + filteredArray[0].offsetWidth / 2;
					} else {
					const filteredArray = daysArray.filter(
						(day) => day.textContent == startDay
					);
					left = filteredArray[0].offsetLeft;
					}

					if (endDay.endsWith('½')) {
					const filteredArray = daysArray.filter(
						(day) => day.textContent == endDay.slice(0, -1)
					);
					width =
						filteredArray[0].offsetLeft + filteredArray[0].offsetWidth / 2 - left;
					} else {
					const filteredArray = daysArray.filter(
						(day) => day.textContent == endDay
					);
					width = filteredArray[0].offsetLeft + filteredArray[0].offsetWidth - left;
					}

					el.style.left = `${left}px`;
					el.style.width = `${width}px`;
					el.style.backgroundColor = el.dataset.color;
					el.style.opacity = 1;
				});
				var altura = $(".chart-wrapper").height();
				console.log(contador);
				console.log(altura);
				altura = altura + 15;
				$('head').append('<style>.chart-wrapper .chart-values li:before{height:'+altura+'px !important;}</style>');
            }
			
            function populateChartValues(dates) {
				const ul = document.querySelector('.chart-values');

				ul.innerHTML = '';

				dates.forEach((date) => {
					const li = document.createElement('li');
					li.innerHTML = "<br>"+date;
					ul.appendChild(li);
				});
            }

            function populateChartBars(tasks) {
				const chartBars = document.querySelector('.chart-bars');
				chartBars.innerHTML = '';

				tasks.forEach((task) => {
					const container = document.createElement('div');
					container.classList.add('zebrinha');
					const li = document.createElement('li');
					const stepLabel = document.createElement('h1');
					li.textContent = task.etapa;

					if(task.aberto==1){
						stepLabel.innerHTML = '<a href="chamado.php?id='+task.chamado+'" target="_blank"><i class="fa-solid fa-caret-right"></i> &nbsp;'+task.etapa+'</a>';
					}
					else{
						stepLabel.innerHTML = '<a href="chamado.php?id='+task.chamado+'" target="_blank"><i class="fa-solid fa-caret-right"></i> &nbsp;'+task.etapa+'&nbsp;&nbsp;<i style="color: #33a76b;" class="fa-solid fa-circle-check"></i></a>';
					}

					const startDate = task.start.getDate();
					const endDate = task.end.getDate();
					const duration = `${startDate}-${endDate}`;

					li.setAttribute('data-duration', duration);
					stepLabel.classList.add('step-label');
					container.classList.add('rowx');
					li.setAttribute('data-color', task.color);
					li.setAttribute('data-chamado', task.chamado);
					li.classList.add('abreChamado');

					container.appendChild(stepLabel);
					container.appendChild(li);
					chartBars.appendChild(container);
				});
            }

            function getDaysInCurrentMonth() {
				const currentDate = new Date();
				const year = currentDate.getFullYear();
				const month = currentDate.getMonth() + 1;

				const lastDayOfPreviousMonth = new Date(year, month - 1, 0);
				const lastDay = lastDayOfPreviousMonth.getDate();

				const daysInMonth = [];
				for (let day = 1; day <= lastDay; day++) {
					daysInMonth.push(day);
				}

				return daysInMonth;
            }

            function getDaysInNextMonth() {
				const currentDate = new Date();
				const year = currentDate.getFullYear();
				const currentMonth = currentDate.getMonth();
				const nextMonth = currentMonth + 1;

				// Verifica se o próximo mês ultrapassa o ano atual
				if (nextMonth > 11) {
					year++;
					nextMonth = 0;
				}

				const lastDayOfNextMonth = new Date(year, nextMonth + 1, 0);
				const lastDay = lastDayOfNextMonth.getDate();

				const daysInMonth = [];
				for (let day = 1; day <= lastDay; day++) {
					daysInMonth.push(day);
				}

				return daysInMonth;
            }

        </script>