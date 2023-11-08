var exampleTasks = [
  {
    etapa: '',
    start: new Date(2023, 10, 1),
    end: new Date(2023, 10, 5),
    color: '#3498db',
  },
  {
    etapa: '',
    start: new Date(2023, 10, 8),
    end: new Date(2023, 10, 15),
    color: '#FFC700',
  },
  {
    etapa: '',
    start: new Date(2023, 10, 16),
    end: new Date(2023, 10, 22),
    color: '#53AF33',
  },
  {
    etapa: '',
    start: new Date(2023, 10, 23),
    end: new Date(2023, 10, 27),
    color: '#FF6600',
  },
];

function createChart(e) {
  const daysOfCurrentMonth = getDaysInCurrentMonth();

  populateChartValues(daysOfCurrentMonth);
  populateChartBars(exampleTasks);

  const days = document.querySelectorAll('.chart-values li');
  const tasks = document.querySelectorAll('.chart-bars li');
  const daysArray = [...days];

  tasks.forEach((el) => {
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

    // apply css
    el.style.left = `${left}px`;
    el.style.width = `${width}px`;
    if (e.type == 'load') {
      el.style.backgroundColor = el.dataset.color;
      el.style.opacity = 1;
    }
  });
}

function populateChartValues(dates) {
  const ul = document.querySelector('.chart-values');

  ul.innerHTML = '';

  dates.forEach((date) => {
    const li = document.createElement('li');
    li.textContent = date;
    ul.appendChild(li);
  });
}

function populateChartBars(tasks) {
  const chartBars = document.querySelector('.chart-bars');
  chartBars.innerHTML = '';

  tasks.forEach((task) => {
    const li = document.createElement('li');
    li.textContent = task.etapa;

    const startDate = task.start.getDate();
    const endDate = task.end.getDate();
    const duration = `${startDate}-${endDate}`;

    li.setAttribute('data-duration', duration);

    li.setAttribute('data-color', task.color);

    chartBars.appendChild(li);
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

window.addEventListener('load', createChart);
