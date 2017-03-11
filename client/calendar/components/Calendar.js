import React, {Component} from 'react';
import Moment from 'moment';
import {extendMoment} from 'moment-range';
const moment = extendMoment(Moment);

moment.locale('cs');


export default class Calendar extends Component {
	constructor(props, context) {
		super(props, context);
		this.state = {month: moment()};
	}

	render() {
		const firstDay = this.state.month.clone().startOf('month').startOf('week');
		const lastDay = this.state.month.clone().endOf('month').endOf('week');
		const days = Array.from(moment.range(firstDay, lastDay).by('days')).map(day => moment(day));

		const chunkSize = 7, weeks = [];
		for (let i = 0; i < days.length; i += chunkSize) {
			weeks.push(days.slice(i, i + chunkSize));
		}

		return (<table className="calendar">
			<thead>
			<tr className="calendar-month">
				<th className="calendar-month-previous">
					<a href="#" onClick={this.previousMonth.bind(this)} title="Předchozí měsíc">
						<span className="fa fa-arrow-left" />
					</a>
				</th>
				<th className="calendar-month-name" colSpan="4">
					{this.state.month.format('MMMM YYYY')}
				</th>
				<th className="calendar-month-now">
					<a href="#" onClick={this.thisMonth.bind(this)} title="Dnes">
						<span className="fa fa-calendar" />
					</a>
				</th>
				<th className="calendar-month-next">
					<a href="#" onClick={this.nextMonth.bind(this)} title="Následující měsíc">
						<span className="fa fa-arrow-right" />
					</a>
				</th>
			</tr>
			<tr class="calendar-days">
				<th>Po</th>
				<th>Út</th>
				<th>St</th>
				<th>Čt</th>
				<th>Pá</th>
				<th>So</th>
				<th>Ne</th>
			</tr>
			</thead>
			<tbody>
			{weeks.map(week => <tr>
				{week.map(day => {
					const hasEvent = !!this.props.eventDates.find(eventDate => day.isSame(eventDate, 'day'));
					return <td className={[
						!day.isSame(this.state.month, 'month') ? 'calendar-days-out' : '',
						day.isSame(new Date, 'day') ? 'calendar-days-today' : '',
						hasEvent ? 'calendar-days-event' : ''
					].join(' ')}>
						{hasEvent ? <a href={`#${day.format('Y-m-d')}`}>
							{day.format('D')}
						</a> : day.format('D')}
					</td>;
				})}
			</tr>)}
			</tbody>
		</table>);
	}

	previousMonth(evt) {
		this.setState({month: this.state.month.clone().subtract(1, 'month')});
		evt.preventDefault();
	}

	thisMonth(evt) {
		this.setState({month: moment()});
		evt.preventDefault();
	}

	nextMonth(evt) {
		this.setState({month: this.state.month.clone().add(1, 'month')});
		evt.preventDefault();
	}
}
